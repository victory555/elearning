<?php
$USERNAME="root";
$PASSWORD="";
try{
    $connect=new PDO("mysql:host=localhost;dbname=elearning",$USERNAME,$PASSWORD);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(exception $e){
$e->getMessage();
}
?>