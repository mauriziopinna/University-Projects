<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    echo "<link rel='stylesheet' href='style.css' type='text/css'>";
    echo "<script src='javascript.js'></script>";
    echo "<script src='jquery-2.2.4.min.js'></script>";
    
    require_once 'functions.php';
    
    require_secure_connection();
    check_cookies_enabled();
    
    $error = $user = $pass = "";
    $diestring = '<div id="heading">Ticket Booking</div>'.
            '<div id="accountcreated"><h5> The account has been created'.
            '<br>Please LogIn or come back to the home</h5></div>'.
            '<form class="form" id="homeloginform">'.
            '<div id="home">'.
            " <a data-role='button' data-inline='true' ".
                " data-transition='slide' href='index.php'>Home</a>".
            "</div><p><br><br></p>".
            '<div id="login">'.
            "<a data-role='button' data-inline='true' ".
               ' data-transition="slide" href="login.php">Login</a>'.
            '</div>'.
           ' </form>';
    //echo <<<_CHECKMAIL
?>
<script>
    function checkUser(user)
    {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (user.value == '')
        {
            $('#used').html('&nbsp;');
            return;
        }
        else if(!(user.value.match(mailformat)))
        {
            $('#used').html('<span class="taken" style="color:red">&nbsp;&#x2718; Email format is not correct</span>');
            return;
        }        
        $.post
        (
            'checkusermail.php',
            { user : user.value },
            function(data)
            {
                $('#used').html(data)
            }
        )        
    }

    function checkPassword(password)
    {
        var passformat = /^.*(?=.*\d)(?=.*[a-zA-Z]).*$/;
        var digit = /.*\d.*/;
        var maiusc = /.*[A-Z].*/;
        var minusc = /.*[a-z].*/;
        if (password.value == '')
        {
            $('#passwordok').html('&nbsp;');
            return;
        }
        if(password.value.match(minusc)&&(password.value.match(maiusc)||password.value.match(digit)))
        {
            $('#passwordok').html('');
        }
        else
        {
            $('#passwordok').html('<span class="taken" style="color:red">&nbsp;&#x2718;Password must contain at least a letter<br> and at least a capital letter or a number</span>');
        }
    }
</script>

<?php
    //_CHECKMAIL;
    
    if (isset($_SESSION['user'])) destroySession(); //questo perche sto facendo un signup
    
    //dopo aver controllato nel client attraverso ajax, faccio un altro controllo nel server
    if (isset($_POST['user'])) //questa variabile viene settata non appena premo il bottone signup
    {
        $user = sanitizeString($_POST['user']);
        $pass = ($_POST['pass']);
        $passhash = md5($pass);
        
        if ($user == "" || $pass == "")
            $error = 'Not all fields were entered<br><br>';
            else
            {
                autocommitoff();
                $result = queryMysql("SELECT * FROM users WHERE mail='$user' FOR UPDATE");
                
                if ($result->num_rows){
                    $error = 'That username already exists<br><br>';
                    rollback();
                    autocommiton();
                }
                else
                {
                    $result = queryMysql("INSERT INTO users VALUES('$user', '$passhash')");
                    commit();
                    autocommiton();
                    die($diestring);
                    //die('<h4>Account created</h4>Please Log in.</div></body></html>');
                    //echo"<h4>Account created</h4>";
                }
            }
    }
    
    echo <<<_END
          <form class="form" id="signupform" method='post' action='signup.php'>
            <div data-role='fieldcontain'>
              <label></label>
              <span class='error'>$error</span>
            </div>
            <div data-role='fieldcontain'>
              <label></label>
              Please enter a valid mail and a password to sign up
              <p></p>
            </div>
            <div class="textdiv" data-role='fieldcontain'>
              <label>Username</label>
              <input type='email' maxlength='40' name='user' value='$user' onBlur=checkUser(this)>
              <label></label><div id='used'>&nbsp;</div>
            </div>
            <div class="textdiv" data-role='fieldcontain'>
              <label>Password</label>
              <input type='password' maxlength='20' name='pass' value='$pass' onkeyup=checkPassword(this)>
              <label></label><div id='passwordok'>&nbsp;</div>
            </div>
            <div data-role='fieldcontain'>
              <label></label>
              <input class="submitbutton" data-transition='slide' type='submit' value='Signup'>
            </div>
          </form>
          <div id="homedivsignup">
            <a id="homebuttid" data-role='button' data-inline='true'
                data-transition="slide" href='index.php'>Home</a>
            </div>
      </body>
    </html>
_END;

?>