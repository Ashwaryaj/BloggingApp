<?php

$servername = $config['db_host'];
$username = $config['db_username'];
$password = $config['db_password'];
$dbName = $config['db_name']; 
//make connection
try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbName",
        $username,
        $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Show 500 internal server error page
    header("location:/500.php");
    die();
}
?>
