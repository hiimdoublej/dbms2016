<?php
$user = "root";
$pwd = "Ladiesman41&";
try {
    $conn = new PDO( "mysql:dbname=dbtest;host=127.0.0.1", $user, $pwd);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch(Exception $e){
    die(var_dump($e));
}
?>