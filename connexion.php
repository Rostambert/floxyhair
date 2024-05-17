<?php
session_start();
$dsn="mysql:dbname=floxyhair;host=localhost";
try{
    $connexion=new PDO($dsn,"root","");
}
catch(PDOException $e){
    printf("Échec de la connexion : %s\n", $e->getMessage());
    exit();
}
?>