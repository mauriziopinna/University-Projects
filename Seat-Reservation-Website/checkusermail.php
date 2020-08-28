<?php
    require_once 'functions.php';
    
    if (isset($_POST['user']))
    {
        $user   = sanitizeString($_POST['user']);
        $result = queryMysql("SELECT * FROM users WHERE mail='$user'");
        
        if (mysqli_num_rows($result))
            echo  "<span class='taken' style='color:red'>&nbsp;&#x2718; " .
            "The username '$user' is taken</span>";
            else
                echo "<span class='available' style='color:green'>&nbsp;&#x2714; " .
                "The username '$user' is available</span>";
    }
?>