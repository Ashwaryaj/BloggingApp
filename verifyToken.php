<?php
ob_start();
session_start();	
ini_set('display_errors', 'on');
error_reporting(E_ALL);
// if session is not set this will redirect to login page
if( !isset($_GET['token']) ) {
	header("Location: forget_password.php");
	exit;
}
include_once 'blogtheme/dbconnect.php';
$nameError = '';
$token=base64_decode($_GET['token']);
$id=base64_decode($_GET['id']);
// Check if token is empty
$res=$conn->prepare("SELECT token FROM tokens WHERE userId=:id");
$res->bindParam(':id',$id);
$res->execute();
$row=$res->fetch(PDO::FETCH_ASSOC);
echo $row['token'];
echo "string";
echo $token;
 // if uname/pass correct it returns must be 1 row. 
if( $row['token']==$token && $res){
    $encodedId=base64_encode($id);
    $encodedToken=base64_encode($token);
	$errTyp = "success";
	$errMSG = "Successful, you entered correctly";
    header("Location: forgetPasswordHome.php?id=$encodedId&&token=$encodedToken");
} else {
	$errTyp = "danger";
	$errMSG = "Something went wrong, try again later...";	
}	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Forget Password</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="assets/style.css" type="text/css" />
</head>
<body>

<div class="container">

	<div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
    	<div class="col-md-12">
        
        	<div class="form-group">
            	<h2 class="">Forget Password</h2>
            </div>
        
        	<div class="form-group">
            	<hr />
            </div>
            
            <?php
			if ( isset($errMSG) ) {
				
				?>
				<div class="form-group">
            	<div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
				<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
            	</div>
                <?php
			}
			?>
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            	<input type="text" name="token" class="form-control" placeholder="Enter Token" maxlength="6"  />
                </div>
                <span class="text-danger"><?php echo $nameError; ?></span>
            </div>
            <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="verify">Verify</button>
            </div>
            
            <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<a href="forget_password.php">Forget Password</a>
            </div>
        
        </div>
   
    </form>
    </div>	

</div>

</body>
</html>
<?php ob_end_flush(); ?>
