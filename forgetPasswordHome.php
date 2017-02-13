<?php
ob_start();	
require_once 'blogtheme/dbconnect.php';
session_start();

ini_set('display_errors', 'on');
error_reporting(E_ALL);

$id=base64_decode($_GET['id']);
$token=base64_decode($_GET['token']);

echo $id;
echo $token;
$res=$conn->prepare("SELECT * FROM tokens WHERE userId=:id");
$res->bindParam(':id',$id);
$res->execute();
$userRow=$res->fetch(PDO::FETCH_ASSOC);
// select logged in users detail
$error = false;
$newPassError = '';
$confPassError = '';

if ( isset($_POST['btn-change_password']) ) {

	function validateInputs($data){
		$data=trim($data);
		$data=strip_tags($data);
		$data=htmlspecialchars($data);
		return $data;
	}

	$new_pass=validateInputs($_POST['new_pass']);
	$conf_pass=validateInputs($_POST['conf_pass']);

	if (empty($new_pass)){
		$error = true;
		$passError = "Please enter password.";
	} else if(strlen($new_pass) < 6) {
		$error = true;
		$newPassError = "Password must have atleast 6 characters.";
	}
	
	if (empty($conf_pass)){
		$error = true;
		$passError = "Please enter password.";
	} else if(strlen($conf_pass) < 6) {
		$error = true;
		$passError = "Password must have atleast 6 characters.";
	}else if($new_pass != $conf_pass){
		$error = true;
		$confPassError = "Password don't match.";
	}
	
	if( !$error ) {
			$query = $conn->prepare("UPDATE users set password=:newPassword where id=:userId");
			$query->bindParam(':newPassword',$new_pass);
			$query->bindParam(':userId',$id);
			if($query->execute()){
			$errTyp = "success";
			$errMSG = "Successfully changed";
			}
	}		
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Blog</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<div class="container">
    
	<div id="login-form">
    <form method="post" action="../ll/forgetPasswordHome.php?id=<?php echo $_GET['id'] ?>&&token=<?php echo $_GET['token']; ?>" autocomplete="off">
    
    	<div class="col-md-12">
        
        	<div class="form-group">
            	<h2 class="">Change Password</h2>
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
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
            	<input type="password" name="new_pass" class="form-control" placeholder="Enter New Password" maxlength="15"/>
                </div>
                <span class="text-danger"><?php echo $newPassError; ?></span>
            </div>
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
            	<input type="password" name="conf_pass" class="form-control" placeholder="Confirm Password" maxlength="15"/>
                </div>
                <span class="text-danger"><?php echo $confPassError; ?></span>
            </div>
            
            <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn-change_password">Change Password</button>
            </div>
             <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<a href="login.php">Login...</a>
            </div>
     
        </div>
   
    </form>
    </div>	

</div>

</body>
</html>
<?php ob_end_flush(); ?>