<?php
//Data base connection 
$user = "tamara";
$pwd = "cukaricki23";
Global $PDO;

$PDO = new PDO('mysql:host=db4free.net;dbname=leadsmanagement', $user, $pwd);

?>