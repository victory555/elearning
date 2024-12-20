<?php        
        session_start();
        // si la session demarre demarer les cookies
        if(isset($_COOKIE[session_name() ])){
           //vide les cookies
           setcookie( session_name(), '' ,time()-8640,'/' ); 
        }
        
        
            
        // supprimer les variables de la session
        session_unset();
        $_SESSION[]=array();
        
        // detruire la session
        session_destroy();
        
        header("Location: index.php");
?>