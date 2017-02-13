<?php
//Send mail for verification
require_once "swiftmailer/lib/swift_required.php";

// Create the Transport
$transport = Swift_SmtpTransport::newInstance("smtp.gmail.com", 587,"tls");//
$transport->setUsername("ashwaryajethi1234@gmail.com");
$transport->setPassword("asimplepassword");

// Create the Mailer using your created Transport
$mailer = Swift_Mailer::newInstance($transport);

// Create the message
$message = Swift_Message::newInstance();

// Give the message a subject
$message->setSubject("Verify your email");

// Set the From address with an associative array
$message->setFrom(array("ashwaryajethi1234@gmail.com" => "Ashwarya"));

// Set the To addresses with an associative array
$message->setTo(array($email=> $name));

$count = $sql -> rowCount(); 

$content="<a href=localhost/ll/verify.php?email=".$encodedEmail."&&token=".$encodedToken.">localhost/ll/verify.php?email=".$encodedEmail."&&token=".$encodedToken."</a>";


$message->setBody($content,'text/html');
// Give it a body
$message->setBody($content,"text/html"); 
// Send the message!
$result = $mailer->send($message);

if(!$result)
{
	echo "Mailer Error: " . $mailer->ErrorInfo;
}
else
{
	$mailMsg="A mail has been sent to your email to verify your 	email";
}	        

?>