<?php
require_once('../bootstrap.php');
//Check if the user is logged in.
if(!isset($_SESSION['username']) || !isset($_SESSION['logged_in'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: login.php');
    exit;
}
if(isset($_POST['title'])){ $title = $_POST['title']; } 
if(isset($_POST['summary'])){ $summary= $_POST['summary'];  }  
if(isset($_POST['content'])){ $content = $_POST['content'];  }  
if ( isset($_POST['btn-create-blog']) || isset($_POST['btn-save-in-drafts'])) {
	$error=false;
    require('../testInput.php');
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
    		require_once('../fetchBloggerId.php');    
    		$status="published";
	        $sql = $conn->prepare("INSERT INTO blogs(title,bloggerId, summary, description,status)  VALUES (? ,? ,?,?,?)");
	        $sql->bindParam(1, $title);
            $sql->bindParam(2, $bloggerId);
	        $sql->bindParam(3, $summary);
	        $sql->bindParam(4, $content);
	        $sql->bindParam(5, $status);
	        $sql->execute();
            $publish=true;
	    }

	    catch(PDOException $e){
	    	// Show 500 internal server error page
	        header("location:../500.php");
	    }
    }


    if(!$error && isset($_POST['btn-save-in-drafts'])){

    	try{
    		require_once('../fetchBloggerId.php');
            $status="draft";
            $sql = $conn->prepare("INSERT INTO blogs(title,bloggerId, summary, description,status)  VALUES (? ,? ,?,?,?)");
            $sql->bindParam(1, $title);
            $sql->bindParam(2, $bloggerId);
            $sql->bindParam(3, $summary);
            $sql->bindParam(4, $content);
            $sql->bindParam(5, $status);
            $sql->execute();
            $draft= true;
	    }

	    catch(PDOException $e){
	    	// Show 500 internal server error page
	        header("location:../500.php");
	    }
    }
}

?>
<?php include_once('../layouts/header.php');?>
    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('../assets/img/home-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Create blog</h1>
                        <hr class="small">
                        <span class="subheading">Create your own blog</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid"> 
	    <div class="row">
			<div id="contact-form" >
				<div>
					<h1>Nice to See You!</h1> 
					<h4>Want to add a blog ?  It's easy!  Just fill in the details.</h4> 
				</div>
				   <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" data-toggle="validator" role="form">
						<div class="form-group">
					      <label for="title" class="control-label">
					      	<span class="required">Blog Title: *</span> 
					      	<input type="text" id="title" name="title" value="" placeholder="Blog title" required="required"  tabindex="1" autofocus="autofocus" />
                            <div class="help-block with-errors"></div>
					      </label> 
                          
						</div>
						<div class="form-group">
					      <label for="summary">
					      	<span class="required">Summary: *</span>
					      	<input type="text" id="summary" name="summary" value="" placeholder="Blog summary" tabindex="2" required="required" />
                            <div class="help-block with-errors"></div>
					      </label>  
						</div>
						<div>		          
					      <label for="content" class="form-group">
					      	<span class="required">Content: *</span> 
					      	<textarea id="content" name="content" placeholder="Please enter the blog content." tabindex="3" required="required"></textarea> 
                            <div class="help-block with-errors"></div>
					      </label>  
						</div>
						<div>		           
					      <button name="btn-save-in-drafts" type="submit" id="save_draft" >Save in drafts</button> 
						</div>
						<div>		           
					      <button name="btn-create-blog" type="submit" id="create_blog"  >Publish</button> 
						</div>
				   </form>
			</div>
		</div>
	</div>
    <hr>

    <footer>
        <div class="container">
            <div class="row">
                 <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="list-inline text-center">
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-github fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; Your Website 2016</p>
                </div>
            </div>
        </div>
    </footer> 


    <!-- jQuery -->
    <script src="../assets/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="../assets/js/jqBootstrapValidation.js"></script>
    <script src="../assets/js/contact_me.js"></script>

    <!-- Theme JavaScript -->
    <script src="../assets/js/clean-blog.min.js"></script>
    <script src="../assets/js/validator.js"></script>
    <script type="text/javascript">
        function show_alert() {
        var msg = "Saved in drafts";
        alert(msg);
        }
        <?php
            if (true == $draft) {
                ?>
                    show_alert();
                <?php
            }
        ?>

    </script>
        <script type="text/javascript">
        function show_published_alert() {
        var msg = "Published";
        alert(msg);
        }
        <?php
            if (true == $publish) {
                ?>
                    show_published_alert();
                <?php
            }
        ?>

    </script>


</body>

</html>
