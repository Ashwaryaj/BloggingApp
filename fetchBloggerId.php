<?php
$email=$_SESSION['email'];
$sql=$conn->prepare("select id from users where email=:email");
$sql->bindParam(":email",$email);
$sql->execute();
$res=$sql->fetch(PDO::FETCH_ASSOC);
$bloggerId=$res['id'];
?>