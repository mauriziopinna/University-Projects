<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    echo "<link rel='stylesheet' href='style.css' type='text/css'>";
    require_once 'functions.php';
    
    require_secure_connection();
    check_cookies_enabled();
    
    //session_start();
    init_session();
    $error = $user = $pass = "";
    
    if(isset($_GET['timeout'])){
        $error = "Session timeout expired, you must re-login to book the seats, or go to the homepage";
    }
    
    if (isset($_POST['user']))
    {
        $user = sanitizeString($_POST['user']);
        $pass = ($_POST['pass']);
        $passhash = md5($pass);
        
        if ($user == "" || $pass == "")
            $error = 'Not all fields were entered';
            else
            {
                $result = queryMySQL("SELECT mail,password FROM users
            WHERE mail='$user' AND password='$passhash' FOR UPDATE");
                
                if ($result->num_rows == 0)
                {
                    $error = "Invalid login attempt";
                }
                else
                {
                    session_start();
                    $_SESSION['user'] = $user;
                    $_SESSION['259444_time'] = time();
                    /*die("<div class='center'>You are now logged in. Please
                 <a data-transition='slide' href='personalpage.php?view=$user'>click here</a>
                 to continue.</div></div></body></html>");*/
                    $address = 'personalpage.php';
                    header('HTTP/1.1 307 temporary redirect');
                    header('Location: ' . $address);
                    exit;
                }
            }
    }
    echo <<<_END
        <div data-role='fieldcontain'>
              <label></label>
              <span class='error'>$error</span>
            </div>
          <form class="form" id="loginform" method='post' action='login.php'>
            <div data-role='fieldcontain'>
              <label></label>
              Please enter your details to log in
              <p></p>
            </div>
            <div class="textdiv" data-role='fieldcontain'>
              <label>Username</label>
              <input type='text' maxlength='40' name='user' value='$user'>
            </div>
            <div class="textdiv" data-role='fieldcontain'>
              <label>Password</label>
              <input type='password' maxlength='20' name='pass' value='$pass'>
            </div>
            <div data-role='fieldcontain'>
              <label></label>
              <input class="submitbutton" data-transition='slide' type='submit' value='Login'>
            </div>
          </form>
            <div id="homedivlogin">
            <a id="homebuttid" data-role='button' data-inline='true'
                data-transition="slide" href='index.php'>Home</a>
            </div>
      </body>
    </html>
_END;

?>