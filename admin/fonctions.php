<?php
function is_email($email){
    return filter_var($email,FILTER_VALIDATE_EMAIL);
}
function check_input($var){
    $var=htmlspecialchars($var);
    $var=strip_tags($var);
    $var=stripslashes($var);
    $var=stripcslashes($var);
    $var=trim($var);
    return $var;
}

?>