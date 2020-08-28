<?php
  /*require_once 'functions.php';
  session_start();
  if (isset($_SESSION['user']))
  {
    destroySession();
    echo "<br><div class='center'>You have been logged out. Please
         <a data-transition='slide' href='index.php'>click here</a>
         to refresh the screen.</div>";
  }
  else echo "<div class='center'>You cannot log out because
             you are not logged in</div>";*/
?>

<?php 
    
    require_once "functions.php";
    
    //$address = $_SERVER['HTTP_REFERER'];
    $address = "index.php";
    
    destroySession();
    header('HTTP/1.1 307 temporary redirect');
    header('Location: ' . $address);
    exit;

?>