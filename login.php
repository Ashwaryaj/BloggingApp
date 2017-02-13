<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require_once('blogtheme/dbconnect.php');
session_start();
if(isset($_SESSION['username']) || isset($_SESSION['logged_in'])){
    //if logged in. Redirect them back to the dashboard page.
    header('Location: blogtheme/dashboard.php');
    exit;
}
// Fetch variables from firstpage.php
if(isset($_POST['user'])){ $user = $_POST['user']; }
if(isset($_POST['my_password'])){ $password = $_POST['my_password'];  }
if ( isset($_POST['btn-sign-in']) ){
    $error=false;
    require_once('testInput.php');
    $user=test_input($user);
    $password = test_input($password);

   //Check if email is empty
    if (empty($user)) {
        $error=true; 
    }

    //Check if password is empty
    if (empty($password)) {
        $error=true;
    }

    if (filter_var($user, FILTER_VALIDATE_EMAIL)) {   
        $email=true;  
    }
    else{
        $username=true;
    }
   
    $records = $conn->prepare('SELECT verified FROM users WHERE (email = :login OR username=:login)');
    $records->bindParam(':login', $user);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    if ('N'==$results['verified']) {
      $error = true;
      $emailErr = "Email not verified. Please verify your email to login.";
    }
    //If there is no error , login
    if(!$error){
                            
        $records = $conn->prepare('SELECT id,username,password,email FROM users WHERE (email = :login OR username=:login)');
        $records->bindParam(':login', $user);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        $results['password'] = password_hash($results['password'], PASSWORD_DEFAULT);
        $validPassword=password_verify($password,$results['password']);
      
      if(count($results) > 0 && $validPassword){
        $_SESSION['username'] = $results['username'];
        $_SESSION['userId']=$results['id'];
        $_SESSION['logged_in'] = time();
        $_SESSION['email']=$results['email'];        
        header('location:blogtheme/dashboard.php');
        exit;
      }
      //If credentials do not match
      else{
        $errMsg = 'Invalid credentials';
        
      }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Login Form</title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">

        <link href="assets/css/login-page.css" rel="stylesheet">
    </head>
    <body>

      <!--login modal-->
      <div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h1 class="text-center">Login</h1>
            </div>
            <div class="modal-body">
                <form class="form col-md-12 center-block" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" data-toggle="validator" role="form">
                  <?php
                  if(isset($errMsg)){
                  ?>  
                  <div class="form-group">
                    <div class="alert alert-danger">
                      <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMsg; ?>
                    </div>
                  </div>
                  <?php
                  }
                  ?>
                  <div class="form-group">
                    <input type="text" class="form-control input-lg" name="user" placeholder="Email/Username" required
                    value="<?php 
                          if($_SERVER['REQUEST_METHOD'] == 'POST'){
                          echo $_POST['user'];
                          }
                          ?>">
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
                    <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control input-lg" name="my_password" placeholder="Password"  required  value="<?php 
                                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                                    echo $_POST['my_password'];
                                    }
                                    ?>"
                      >
                    <div class="help-block with-errors" ></div>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary btn-lg btn-block" name="btn-sign-in">Sign In</button>
                    <span class="pull-right"><a href="registration.php">Register</a></span><span><a href="forget_password.php" name="btn-forget_password">Forgot password</a></span>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        </div>
      </div>

          <!-- script references -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/validator.js"></script>
    </body>
  </html>