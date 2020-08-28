<?php
    require_once 'functions.php';
    $expired = init_session();
    //session_start();
    if($expired == 0)
        die("0");
        
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
        
        if(isset($_POST['nseats'])){
            $seats = $_POST['nseats'];
            autocommitoff();
            $result = queryMysql("SELECT * FROM seatmap WHERE status='booked' and mail='$user' FOR UPDATE");
            if(mysqli_num_rows($result) != intval($seats)){
                $result = queryMysql("DELETE FROM seatmap WHERE status='booked' and mail='$user' ");
                echo "IT WAS NOT POSSIBLE TO DO THE PURCHASE. SEAT WILL BE VACATED";
                //per essere visualizzato il nuovo stato, dev'essere ricaricata la pagina
            }
            else{
                $result = queryMysql("UPDATE seatmap SET status='purchased' WHERE mail='$user' ");
                echo "PURCHASE DONE!";
            }
            
            if($result)
                commit();
                else
                    rollback();
                    
                    autocommiton();
        }
    }
        
    
?>