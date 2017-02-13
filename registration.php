<?php
session_start();
ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once('blogtheme/dbconnect.php');

if(isset($_POST['my_name'])){ $name = $_POST['my_name']; }
if(isset($_POST['my_email'])){ $email = $_POST['my_email'];  }  
if(isset($_POST['my_username'])){ $username = $_POST['my_username'];  }  
if(isset($_POST['my_password'])){ $password = $_POST['my_password'];  } 
if(isset($_POST['my_confirm_password'])){ $confirm_password = $_POST['my_confirm_password'];  } 


if ( isset($_POST['btn-sign-in']) ) {
	$error=false;
// clean user inputs to prevent sql injection

	require_once('testInput.php');
    $name = test_input($name);
    $email=test_input($email);
    $username=test_input($username);


    //Check if name is empty
    if (empty($name)) {
        $error=true;   
    }
    //Check if name matches the required format
    else if(!preg_match("/^[A-Za-z ]*$/", $name))
    {
    	$error = true;
    }

   //Check if email is empty
    if (empty($email)) {
        $error=true; 
    }
    // check if e-mail address is well-formed
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {   
        $error=true;  
    }
    // Check for email already exists
    else{

    	$sql = $conn->prepare("SELECT * FROM users WHERE email=?");
        $sql->bindParam(1, $email);
        $sql->execute();
        $count = $sql -> rowCount();
        // Check if email is in use
        if($count!=0){
            $error = true;
            $emailErr = "Provided Email is already in use.";
        }
 
   }

    //Username field is empty
    if (empty($username)) {
        $error=true;
    }
    // Check for Length of username 
    else if(strlen($username)>12)
    {
        $error=true;
    }

    else {
        // Check if username already exists
        $sql = $conn->prepare("SELECT * FROM users WHERE username=?");
        $sql->bindParam(1, $username);
        $sql->execute();

        $count = $sql -> rowCount();
        // Check if email is in use
        if($count!=0){
            $error = true;
            $ErrorMsg = "Username is already in use.";
        }
    }


    //Password field is empty
    if (empty($password)) {
        $error=true; 
    }


    //Confirm Password field is empty
    if (empty($confirm_password)) {
        $error=true;
    }
    // Whether both the passwords match
    else if ($password!=$confirm_password) {
        $error=true;
    } 

    if(!$error){

	    try{

	    	function getRandomString($length){
                $validCharacters = "ABCDEFGHIJKLMNPQRSTUXYVWZ123456789";
                $validCharNumber = strlen($validCharacters);
                $result = "";

                for ($i = 0; $i < $length; $i++) {
                    $index = mt_rand(0, $validCharNumber - 1);
                    $result .= $validCharacters[$index];
                }
                return $result;
            }

            $token=getRandomString(6);
	        // prepare and bind
	        $sql = $conn->prepare("INSERT INTO users(name,email,username,password,token)  VALUES (? ,? ,?, ? , ?)");
	        $sql->bindParam(1, $name);
	        $sql->bindParam(2, $email);
	        $sql->bindParam(3, $username);
	        $sql->bindParam(4, $password);
	        $sql->bindParam(5, $token);
	        $sql->execute();
	        $encodedEmail=base64_encode($email);
	        $encodedToken=base64_encode($token);
		
	        require_once('sendMail.php');
	        // Redirect to login page
	    }   
	    catch(PDOException $e){
	    	// Show 500 internal server error page
	        header("location:500.php");
	    }
	}   
}
?> 
<!DOCTYPE html>
<html lang="en">
    <head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

		<!-- Custom CSS style -->
		<link rel="stylesheet" type="text/css" href="assets/css/login-page.css">

		<!-- Google Fonts -->
		<link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

		<!-- Website Font style -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
	
		
		<title>Admin</title>
	</head>
	<body>
		
		<div class="container">
			<div class="row main">
				<div class="panel-heading">
	               <div class="panel-title text-center">
	               		<h1 class="title">Please register here</h1>
	               		<hr />
	               	</div>
	            </div> 
				<div class="main-login main-center">
					<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" data-toggle="validator" role="form">	
						<div class="form-group">

							<label for="name" class="cols-sm-2 control-label">Your Name</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="my_name" id="name"  required pattern="^[A-Za-z ]*$"    placeholder="Enter your Name" 
									<?php echo $_SERVER['REQUEST_METHOD'];?>
									value="<?php 
						                  if('POST'== $_SERVER['REQUEST_METHOD'] ){
						                  echo $_POST['my_name'];
						                  }
						                  ?>"
						            />
									
								</div>
							</div>
							<div class="help-block with-errors"></div>
						</div>

						<div class="form-group">
							<label for="email" class="cols-sm-2 control-label">Your Email</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
									<input type="email"  required class="form-control" id="email" name="my_email" placeholder="Enter your Email"				value="<?php 
						                  if(isset($_POST['my_email'])){
						                  	echo $_POST['my_email'];
						                  }
						                  ?>"
									/>
								</div>
							</div>
							<div class="help-block with-errors"></div>	
						</div>
						<!-- Check for emailErr -->
						<?php
						if(isset($emailErr)){
							?>	
							<div class="form-group">
			            	<div class="alert alert-danger">
							<span class="glyphicon glyphicon-info-sign"></span> <?php echo $emailErr; ?>
			                </div>
			            	</div>
			                <?php
						}
						?>
						<!-- Check for mailMsg -->
						<?php
						if(isset($mailMsg)){
							?>	
							<div class="form-group">
			            	<div class="alert alert-success">
							<span class="glyphicon glyphicon-ok"></span> <?php echo $mailMsg; ?>
			                </div>
			            	</div>
			                <?php
						}
						?>
						<div class="form-group">
							<label for="username" class="cols-sm-2 control-label">Username</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control"  id="username" name="my_username" maxlength=12 placeholder="Enter your Username" autocomplete="off" required
									value="<?php 
						                  if(isset($_POST['my_username'])){
						                  echo $_POST['my_username'];
						                  }
						                  ?>"
									/>
									
								</div>
							</div>
							<div class="help-block with-errors"></div>
						</div>
						<!--Check for ErrorMsg  -->
						<?php
						if(isset($ErrorMsg)){
							?>	
							<div class="form-group">
			            	<div class="alert alert-danger">
							<span class="glyphicon glyphicon-info-sign"></span> <?php echo $ErrorMsg; ?>
			                </div>
			            	</div>
			                <?php
						}
						?>

						<div class="form-group">
							<label for="password" class="cols-sm-2 control-label">Password</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
									<input type="password" class="form-control"  id="password"  name="my_password" required autocomplete="off"placeholder="Enter your Password"
									value="<?php 
						                  if(isset($_POST['my_password'])){
						                  echo $_POST['my_password'];
						                  }
						                  ?>"
									/>
									
								</div>
							</div>
							<div class="help-block with-errors"></div>
						</div>						

						<div class="form-group">
							<label for="confirm" class="cols-sm-2 control-label">Confirm Password</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
									<input type="password" required class="form-control" id="confirm" name="my_confirm_password"  data-match="#password" data-match-error="Whoops, these don't match" placeholder="Confirm your Password" 
									value="<?php 
						                  if(isset($_POST['my_confirm_password'])){
						                  echo $_POST['my_confirm_password'];
						                  }
						                  ?>"
									/>
									
								</div>
							</div>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group ">
							<button type="submit" name="btn-sign-in" class="btn btn-primary btn-lg btn-block login-button">Register</button>
						</div>
						<div class="login-register">
				            <a href="login.php">Login</a>
				        </div>
					</form>
				</div>
			</div>
		</div>
		<script src="assets/js/jquery.js"></script>
		<script type="text/javascript" src="assets/js/bootstrap.js"></script>
		<script src="assets/js/validator.js"></script>
	</body>
</html>
