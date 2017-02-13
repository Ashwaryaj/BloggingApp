<?php
if (!isset($_GET['email'])  || !isset($_GET['token']))  {
	header("location: 500.php");
}

require_once('blogtheme/dbconnect.php');

$email= base64_decode($_GET['email']);
$token=base64_decode($_GET['token']);

$sql = $conn->prepare("SELECT * FROM users WHERE email=?");
$sql->bindParam(1, $email);
$sql->execute();
$row=$sql->fetch(PDO::FETCH_ASSOC);
$count = $sql -> rowCount();
if($row['token']==$token && $count && $sql){
    echo "Email is verified";
    $userEmail = $row['email'];
    $new = 'Y';
    $query = $conn->prepare("UPDATE users SET verified=:newVerified WHERE email=:email");
    $query->bindValue(':newVerified', $new);
    $query->bindParam(':email',$userEmail);
    $query->execute();

}
else{
    echo "Something went wrong. Please try again later";
}
?>