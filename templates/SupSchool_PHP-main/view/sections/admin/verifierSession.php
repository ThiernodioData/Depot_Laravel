<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(!$_SESSION["email"]){
        header(
            "Location:login?error=1&message=" 
            . urlencode("Merci de vous connecter !!") . 
            "&title=" . urlencode("Acces Refusé !!") 
        );
        exit;
    }
?>

