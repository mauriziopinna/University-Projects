<?php
    $dbhost = 'localhost';
    $dbname = 's259444';
    $dbuser = 's259444';
    $dbpass = 'sorsthen';
    
    
    //echo <<<_INCLUDE
?>
        
<?php
    //_INCLUDE;
    
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    if (!$connection)
        die("Fatal Error");
    
    function queryMysql($query)
    {
        global $connection;
        $result = mysqli_query($connection, $query);
        if (!$result) echo "Fatal Error query";
        return $result;
    }
    
    function autocommitoff(){
        global $connection;
        mysqli_autocommit($connection, false);
    }
    
    function autocommiton(){
        global $connection;
        mysqli_autocommit($connection, true);
    }
    
    function commit(){
        global $connection;
        mysqli_commit($connection);
    }
    
    function rollback(){
        global $connection;
        mysqli_rollback($connection);
    }
    
    function beginTrans(){
        global $connection;
        mysqli_begin_transaction($connection, MYSQLI_TRANS_START_READ_WRITE);
    }
    
    function destroySession()
    {
        $_SESSION = array();
    
        if (session_id() != "" || isset($_COOKIE[session_name()]))
            setcookie(session_name(), '', time() - 2592000, '/');
    
        session_destroy();
    }
    
    
    
    function sanitizeString($var)
    {
        global $connection;
        $var = strip_tags($var);
        $var = htmlentities($var);
        if (get_magic_quotes_gpc())
            $var = stripslashes($var);
        return mysqli_real_escape_string($connection, $var);
    }
    
    
    function getStatus($id){
        if(strlen($id)>2){
            $line = $id[1].$id[2];
        }
        else
            $line = $id[1];
        $line = intval($line);
        $seat = $id[0];
        $result = queryMysql("SELECT * FROM seatmap WHERE (line='$line' and seat='$seat') FOR UPDATE ");
        
        if(isset($_SESSION['user'])){
            if(!mysqli_num_rows($result))
                return '"free" onclick="bookseat(this)" ';
            else
            {
                $entry = mysqli_fetch_array($result, MYSQLI_NUM);
                if($entry[2]=="booked"){                       
                    $user = $_SESSION['user'];
                    if($entry[3]=="$user")
                        return ' "booked" onclick="bookseat(this)" ' ;
                    else
                        return ' "prevbooked" onclick="bookseat(this)" ';
                }                    
                else if($entry[2]=="purchased")
                    return ' "purchased"';
            }
        }
        
        else{ //utente non loggato, nessun posto deve essere cliccabile
            if(!mysqli_num_rows($result))
                return '"free" ';
            else
            {
                $entry = mysqli_fetch_array($result, MYSQLI_NUM);
                if($entry[2]=="booked"){
                    return ' "prevbooked" ';
                }
                else if($entry[2]=="purchased")
                    return ' "purchased"';
            }
        }
       
    }
    
    function getfree(){
        $result = queryMysql("SELECT status FROM seatmap WHERE status='free' ");
        return mysqli_num_rows($result);
    }
    
    function getbooked(){
        $result = queryMysql("SELECT status FROM seatmap WHERE status='booked' ");
        return mysqli_num_rows($result);
    }
    
    function getpurchased(){
        $result = queryMysql("SELECT status FROM seatmap WHERE status='purchased' ");
        return mysqli_num_rows($result);
    }
    
    function require_secure_connection()
    {
        if( empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on" )
        {
            destroySession();
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            exit();
        }
    }
    
    function init_session(){
        session_start();
        $t = time();
        $difference = 0;
        
        if(isset($_SESSION['259444_time']))
        {
            $t0 = $_SESSION['259444_time'];
            $difference = ($t-$t0);
        }
        else
        {
            destroySession();
            return;
        }
        
        if( ( $difference > 120 ) ) // l'utente puo stare massimo 2 minuti senza eseguire un azione
        {
            
            destroySession();
            //header('HTTP/1.1 307 temporary redirect');
           return 0;               
           //header('Location: login.php?timeout=true');
        }
        
        $_SESSION['259444_time'] = time(); // aggiorno il timer
        return 1;
    }
    
    function check_cookies_enabled()
    {
        setcookie("259444_cookie", "test", time() + 3600, '/');
        
        if(count($_COOKIE) <= 0)
        {
            header('HTTP/1.1 307 temporary redirect');
            header('Location: nocookies.php');
            /*if( isset($_GET['no_cookies']) )
            {
                header('HTTP/1.1 307 temporary redirect');
                header('Location: nocookies.php');
                exit;
            }
            else
            {
                header('HTTP/1.1 307 temporary redirect');
                header('Location: index.php?no_cookies=true');
                //header("Location: " . $_SERVER["REQUEST_URI"] . "?no_cookies=true");
                exit;
            }*/
        }
    }
    
    function check_dimension_changed($length, $width){
        $result = queryMysql("SELECT value FROM dimension WHERE dimension ='width' ");
        $widthdb = mysqli_fetch_array($result);
        $widthdb = $widthdb[0];
        
        $result = queryMysql("SELECT value FROM dimension WHERE dimension ='length' ");
        $lengthdb = mysqli_fetch_array($result);
        $lengthdb = $lengthdb[0];
        
        if($widthdb != $width || $lengthdb != $length){
            queryMysql("UPDATE dimension SET value = '$width' WHERE dimension = 'width' ");
            queryMysql("UPDATE dimension SET value = '$length WHERE dimension = 'length' ");
            queryMysql("DELETE FROM seatmap");
            echo "SEAT DIMENSION HAS BEEN CHANGED BY THE ADMIN OF THE WEBSITE, SO ALL SEATS ARE NOW FREE";
        }
    }
?>