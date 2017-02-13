<?php
//for displaying errors
ini_set('display_errors', 'on');
error_reporting(E_ALL);

//session_start();
$servername = "localhost";
$username = "root";
$password = "mindfire";
//make connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=Blogging1", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    
    	// Show 500 internal server error page
    	header("location:/500.php");
    	die();

    }
?>