<?php
    require_once 'functions.php';
    init_session();
    require_secure_connection();
    
    require_once 'header.php';
    
    if (!$loggedin) die(); /*dentro il die puoi stampare roba anche di html, guarda l'esempio members.php*/
    
?>