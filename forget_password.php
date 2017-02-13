<?php
ob_start();
session_start();

ini_set('display_errors', 'on');
error_reporting(E_ALL);
if( isset($_SESSION['username'])!="" ){
    header("Location: dashboard.php");
}
include_once('blogtheme/dbconnect.php');
$error = false;
// If forget password button is pressed
if ( isset($_POST['btn-forget_password']) ) {
    // clean user inputs to prevent sql injections     
    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);


    //basic email validation
    if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
        $error = true;
        $emailError = "Please enter valid email address.";
    } 
}
if( !$error ) {
    // Check if email exists
    $query=$conn->prepare("SELECT id FROM users WHERE email=:email");
    $query->bindParam(':email', $email);
    $query->execute();
    $count = $query->fetch(PDO::FETCH_ASSOC);
    $id=$count['id'];
    if($count==0){
    $error = true;   
    $emailError = "This email address is not registered";
    }
    else{
        //Generate token
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
    //Delete entry if  token already exists
    $delquery = $conn->prepare("DELETE FROM `tokens` WHERE userId=:id");
    $delquery->bindParam(':id',$id);
    $delquery->execute();
    $query=$conn->prepare("INSERT INTO tokens (token,userId) VALUES (:token,:id)");
    $query->bindParam(':token',$token);
    $query->bindParam(':id',$id);
    $query->execute();
    // Send a mail reset link
    function mailresetlink($to,$token,$id){
        
        require_once "swiftmailer/lib/swift_required.php";
        $transport = Swift_SmtpTransport::newInstance("smtp.gmail.com", 587,"tls");//
        $transport->setUsername("ashwaryajethi1234@gmail.com");
        $transport->setPassword("asimplepassword");

        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        // Create the message
        $message = Swift_Message::newInstance();

        // Give the message a subject
        $message->setSubject("Forgot Password Link");

        // Set the From address with an associative array
        $message->setFrom(array("ashwaryajethi1234@gmail.com" => "Ashwarya"));

        // Set the To addresses with an associative array
        $message->setTo(array($to=> "Hi"));

        $encodedId=base64_encode($id);
        $encodedToken=base64_encode($token);
        //$content="Your token is ".$token;
        $content="<a href=localhost/ll/verifyToken.php?id=".$encodedId."&&token=".$encodedToken.">localhost/ll/verifyToken.php?id=".$encodedId."&&token=".$encodedToken."</a>";

        // Give it a body
        $message->setBody($content,"text/html"); 
        // Send the message!
        
        $result = $mailer->send($message);
        echo "string";
        if(!$result)
        {
            echo "Mailer Error: " . $mailer->ErrorInfo;
        }
        else
        {
            $mailMsg="Link sent";
        }
    }
    if(isset($_POST['email'])){
        echo "it is sending";
        mailresetlink($email,$token,$id);          
    }    

}
}

    
    
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Login & Registration</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="assets/style.css" type="text/css" />
</head>
<body>
<div class="container">
    <div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
        <div class="col-md-12">
            <div class="form-group">
                <h2 class="">Send Me Reset Instruction</h2>
            </div>
            <div class="form-group">
                <hr />
            </div>
            <?php
            if ( isset($errMSG) ) { 
                ?>
                <div class="form-group">
                <div class="alert alert-danger">
                <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
                </div>
                <?php
            }
            ?>
            <div class="form-group">
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                <input type="email" name="email" class="form-control" placeholder="Your Email" value="<?php echo $email; ?>" maxlength="40" />
                </div>
                <?php if (isset($emailError)) {?>
                <span class="text-danger"><?php echo $emailError; ?></span>
                <?php }?>
            </div>
            <div class="form-group">
                <hr />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary" name="btn-forget_password">Send Me Reset Instruction</button>
            </div>            
            <div class="form-group">
                <hr />
            </div>            
            <div class="form-group">
                <a href="registration.php">Sign Up Here...</a>
            </div>
            <div class="form-group">
                <hr />
            </div>
            <div class="form-group">
                <a href="login.php">Log In...</a>
            </div>       
        </div>
    </form>
    </div>  
</div>
</body>
</html>
<?php ob_end_flush(); ?>