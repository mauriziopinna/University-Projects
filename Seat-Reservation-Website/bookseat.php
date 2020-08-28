<?php
    require_once 'functions.php';
    //session_start();
    $expired = init_session();
    if($expired == 0)
        die ("0");
    
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
        
        if(isset($_POST['id'])){
            $id = sanitizeString($_POST['id']);
            if(isset($_POST['status'])){
                $status = sanitizeString($_POST['status']);
            }
            if(strlen($id)>2){
                $line = $id[1].$id[2];
            }
            else{
                $line = $id[1];
            }
            $line = intval($line);
            $seat = $id[0];
            
            autocommitoff();
            //beginTrans();
            
            $result = queryMysql("SELECT * FROM seatmap WHERE (line='$line' and seat='$seat') FOR UPDATE ");
            $entry = mysqli_fetch_array($result, MYSQLI_NUM);
            
            if($status == "free" or $status=="prevbooked"){
                //l'utente VEDE il posto libero o occupato da un altro utente e vuole prenotarlo
                if(!mysqli_num_rows($result)){
                    $result = queryMysql("INSERT INTO seatmap VALUES('$line', '$seat', 'booked', '$user')");
                    echo "4";
                }
                else if($entry[2]=="booked" && $entry[3]!= $user){
                    $result = queryMysql("UPDATE seatmap SET status='booked', mail='$user' WHERE (line='$line' and seat='$seat') ");
                    echo "4";
                }
                else if($entry[2]=="purchased"){
                    echo("5");
                }
            }
            else if ($status=="booked"){ //l'utente vede il posto occupato da se stesso e vuole liberarlo
                if(!mysqli_num_rows($result)){
                    echo "3"; //un altro utente nel frattempo aveva sovrascritto la prenotazione e l'ha poi liberato
                }//quindi ora il posto è libero
                else if($entry[2]=="booked" && $entry[3]== $user){ //il posto è ancora prenotato a suo nome e viene sprenotato
                    $result = queryMysql("DELETE from seatmap WHERE mail='$user' and line='$line' and seat= '$seat' ");
                    echo "3";
                }
                else if($entry[2]=="booked" && $entry[3]!= $user){
                    //nel mentre un altro utente ha sovrascitto la prenotazione, quindi deve apparire arancione
                    echo "2";
                }
                else if($entry[2]=="purchased"){//nel mentre un altro utente ha acquistato il posto che vuole sprenotare
                    echo("5");
                }
            }
            
            if($result)
                commit();
                else
                    rollback();
                    
                    autocommiton();
                    
        }        
        
    }
    
          
    
    

?>