<?php
session_start();
ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once('dbconnect.php');
//Check if the user is logged in.
if(!isset($_SESSION['username']) || !isset($_SESSION['logged_in'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: ../login.php');
    exit;
}
$blogId=$_GET['blogId'];

try{
	$sql = $conn->prepare("DELETE  FROM blogs WHERE id=:id");
	$sql->bindParam(":id",$blogId);
	$sql->execute();
	if ($sql) {
		header("location:../blogtheme/dashboard.php");
	}

}
catch(PDOException $e){
	// Show 500 internal server error page
    
    header("location:../500.php");
}

?>