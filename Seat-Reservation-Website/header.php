<?php //myapp
    require_once 'functions.php';
    define("WIDTH", 6);  //CHANGE THIS TWO CONSTANTSIF YOU WANT TO CHANGE THE SEATMAP
    define("LENGHT", 10);
    //session_start(); //dev'essere in ogni pagina
    check_cookies_enabled();
    $seats=array('A','B','C','D','E','F','G','H','I','L','M','N','O');
    
    check_dimension_changed(LENGHT, WIDTH);
?>

    <html>
      <head>
        <meta charset='utf-8'>
        <link rel='stylesheet' href='style.css' type='text/css'>
        <script src='javascript.js'></script>
        <script src='jquery-2.2.4.min.js'></script>
      </head>
      <body>
    <div class="heading">Ticket Booking</div>
        
<?php
    $userstr = "Welcome. You have to login for booking the seats";    
    if (isset($_SESSION['user']))
    {            
        $user = $_SESSION['user'];
        $loggedin = TRUE;
        $userstr  = "Logged in as: $user";
    }
    else {$loggedin = FALSE;}    
?>
      <script>
        var seatslocal = [];
        var i=0;
        function buyseats(){
        	var numseats = $('.booked').length;
            if (numseats == 0){
                alert("YOU HAVE TO BOOK AT LEAST ONE SEAT FIRST!");
                return;
            }
            $.post
            ('buy.php',
            { nseats : numseats },
            function(retbuy)
            {
                if(retbuy == 0){
                	alert("Session timeout expired! You have to re-login!");
                	window.location = "login.php";
                }
                else{
                	alert(retbuy);
                    location.reload(true);
                }               	
                
            }
            );            
        }
        
        function bookseat(elem)
        {
        	$.post
            (
            'bookseat.php',
            { id : elem.id, status : elem.className },
            function(retval)
            {
                len = retval.length;
                if(retval[len-1] == "0"){
					alert("Session timeout expired! You have to re-login!");
					window.location = "login.php";
                }
                if(retval[len-1] == "4")
                {
                    elem.className = "booked";                  
                }
                else if(retval[len-1] == "2")
                {
                    elem.className = "prevbooked";
                }
                else if(retval[len-1] == "3"){
                    elem.className = "free";
                }
                else if(retval[len-1]=="5")
                {
                    elem.className = "purchased";
                    elem.removeAttribute("onclick");
                    alert("Sorry! This seat was previously booked by another user!");
                }
            },"text"
            );          
            
        }
      </script>
      <noscript><p id="nojava">Your browser does not support JavaScript or you have disable it!
      <br> The site might not work correctly!</p>
      </noscript>
<?php 
    echo "<div class='username'>$userstr</div>";
      
    if($loggedin){
        echo '<div id="tablediv">';
        echo '<table id="seatmap">';
        for($i=1; $i<LENGHT+1; $i++){
            echo "<tr>";
            for($j=0; $j<WIDTH; $j++){
                $id = $seats[$j].$i;
                echo '<td id='.$id.' class= '.getStatus($id). ' >';
                echo $i.$seats[$j];
                echo "</td>";
                if($j==2)
                    echo '<td class="corridor"></td>';
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        
        echo <<<_BUY
        <div id=buyupdate>
        <div id="buydiv">
        <input type="button" id="buybuttid" value="BUY" onclick="buyseats()">
        </div>
        <p></p>
        <div id="updatediv">
        <input type="button" id="updatebuttid" value="UPDATE" onclick=location.reload(true)>
        </div>
        <p><br><br><br><br></p>
        <div id="logoutdiv">
        <a id="logoutid" data-role='button' data-inline='true'
            data-transition="slide" href='logout.php'>Logout</a>
        </div>
        </div>
_BUY;
        
    }
    
    else{
        echo <<<_SIGNLOG
        <div class="login-page">
        <div class="form" id="headerform">
        <form class="login-form">
        <div id="signup">
        <a data-role='button' data-inline='true'
            data-transition="slide" href='signup.php'>Sign Up</a>
        </div>
        <div id="login">
        <a data-role='button' data-inline='true'
            data-transition="slide" href='login.php'>Login</a>
        </div>
        </form>
        </div>
        </div>
_SIGNLOG;
        
        echo "<div id='summary'>";
        echo "<p>Total number of seats in the plane: ";
        echo WIDTH*LENGHT;
        echo "</p>";
        echo "<p class='freeseats' > Number of free seats: " .getfree(). "</p>";
        echo "<p class='bookedseats'> Number of booked seats: " .getbooked(). "</p>";
        echo "<p class='purchasedseats'> Number of purchased seats: " .getpurchased(). "</p>";
        echo "</div>";
        
        
        echo '<div id="tablediv">';
        echo '<table id="seatmap">';
        for($i=1; $i<LENGHT+1; $i++){
            echo "<tr>";
            for($j=0; $j<WIDTH; $j++){
                $id = $seats[$j].$i;
                echo '<td id='.$id.' class= '.getStatus($id).' >';
                echo $i.$seats[$j];
                echo "</td>";
                if($j==2)
                    echo '<td class="corridor"></td>';
                
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }
?>