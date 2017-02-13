<?php 
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require_once('dbconnect.php');
if ($blogId=$_GET['blogId']) {
    # code...
    //echo $blogId;
}
$sql = $conn->prepare("SELECT * FROM blogs WHERE id=:blogId");
$sql->bindParam(":blogId",$blogId);
$sql->execute();
$row = $sql->fetch(PDO::FETCH_ASSOC);
$title = $row['title'];
$summary=$row['summary'];
$content=$row['description'];
//echo $content;
if(isset($_POST['title'])){ $title = $_POST['title']; } 
if(isset($_POST['summary'])){ $summary= $_POST['summary'];  }  
if(isset($_POST['content'])){ $content = $_POST['content'];  }  


if ( isset($_POST['btn-create-blog']) || isset($_POST['btn-save-in-drafts'])) {
	$error=false;
	echo $error;
// clean user inputs to prevent sql injection
    function test_input($data) {
        $data = trim($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    $title = test_input($title);
    $summary=test_input($summary);
    $content=test_input($content);


    //Check if title is empty
    if (empty($title)) {
        $error=true;   
    }

    //Check if summary is empty
    if (empty($summary)) {
        $error=true;   
    }

    //Check if content is empty
    if (empty($content)) {
        $error=true;   
    }

    if(!$error && isset($_POST['btn-create-blog'])){

    	try{ 
            //$blogId=$_GET['blogId'];
    		$status="Published";
	        $sql = $conn->prepare("update blogs set title=? , summary=?, description=?, status=? where id=?");
	        $sql->bindParam(1, $title);
	        $sql->bindParam(2, $summary);
	        $sql->bindParam(3, $content);
	        $sql->bindParam(4, $status);
	        $sql->bindParam(5, $blogId);
	        $sql->execute();
            echo "<script type='text/JavaScript'>alert('Edited and Published')</script>";
            
	    }

	    catch(PDOException $e){
	    	// Show 500 internal server error page
	        echo $sql->queryString . "<br>" . $e->getMessage();
	        //header("location:500.php");
	    }
    }

    if(!$error && isset($_POST['btn-save-in-drafts'])){

    	try{
    		$status="Draft";
            $sql = $conn->prepare("update blogs set title=? , summary=?, description=?, status=? where id=?");	        
            $sql->bindParam(1, $title);
	        $sql->bindParam(2, $summary);
	        $sql->bindParam(3, $content);
	        $sql->bindParam(4, $status);
	        $sql->bindParam(5, $blogId);
	        $sql->execute();
            echo "<script type='text/JavaScript'>alert('Edited and saved in drafts')</script>";
	    }

	    catch(PDOException $e){
	    	// Show 500 internal server error page
	        header("location:../500.php");
	    }
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit your post</title>

    <!-- Bootstrap Core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="../assets/css/clean-blog.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="../assets/css/create-blog.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    Menu <i class="fa fa-bars"></i>
                </button>
                <p class="navbar-brand" >
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="../blogtheme/dashboard.php">Home</a>
                    </li>
                    <li>
                        <a href="../blogtheme/createBlog.php">Post</a>
                    </li>
                    <li>
                        <a href="../blogtheme/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('../assets/img/home-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Edit post</h1>
                        <hr class="small">
                        <span class="subheading">Feel free to edit your post</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid"> 
	    <div class="row">
			<div id="contact-form" >
				<div>
					<h1>Please update the post.</h1> 
				</div>

				   <form method="post"  action="../blogtheme/editBlog.php?blogId=<?php echo $blogId ?>" data-toggle="validator" role="form">
						<div class="form-group">
					      <label for="title" class="control-label">
					      	<span class="required">Blog Title: *</span> 
					      	<input type="text" id="title" name="title"  placeholder="Blog title" required="required" tabindex="1" autofocus="autofocus" value="<?php
					      			echo $title;
					      			?>"
					      	/>
					      </label> 
						</div>
						<div class="form-group">
					      <label for="summary">
					      	<span class="required">Summary: *</span>
					      	<input type="text" id="summary" name="summary" placeholder="Blog summary" tabindex="2" required="required"  
					      	value="<?php 
					      			echo $summary;
					      			?>"
					      	/>
					      </label>  
						</div>
						<div>		          
					      <label for="content" class="form-group">
					      	<span class="required">Content: *</span> 
					      	<textarea id="content" name="content" placeholder="Blog content." tabindex="3" required="required"
					      	>
                            <?php
                            echo $content;
                            ?>
					      	</textarea> 
					      </label>  
						</div>
						<div>		           
					      <button name="btn-save-in-drafts" type="submit" id="save_draft" >Save in drafts</button> 
						</div>
						<div>		           
					      <button name="btn-create-blog" type="submit" id="create_blog" >Publish</button> 
						</div>
				   </form>
			</div>
		</div>
	</div>
	<script src="../assets/js/validator.js"></script>
    <!-- jQuery -->
    <script src="../assets//jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../assets/../ll/assets/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="../assets/js/jqBootstrapValidation.js"></script>
    <script src="../assets/js/contact_me.js"></script>

    <!-- Theme JavaScript -->
    <script src="../assets/js/clean-blog.min.js"></script>

</body>

</html>
