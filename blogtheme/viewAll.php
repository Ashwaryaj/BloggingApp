<?php
session_start();
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require_once('dbconnect.php');
//Check if the user is logged in.
if(!isset($_SESSION['username']) || !isset($_SESSION['logged_in'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: ../login.php');
    exit;
}

if(isset($_POST['comment-box'])){ $comment = $_POST['comment-box']; }
if(isset($_POST['hidden-blog-id'])){ $blogId = $_POST['hidden-blog-id']; }
if(isset($_SESSION['userId'])){$uname=$_SESSION['userId'];}
if(isset($_POST['submit']) ) {
    //echo "Set";
    try{
        $sql = $conn->prepare("INSERT INTO comments(blogId,bloggerId,commentText) VALUES (? ,?,? )");
        $sql->bindParam(1, $blogId);
        $sql->bindParam(2, $uname);
        $sql->bindParam(3, $comment);
        $sql->execute();
        
    }
    catch(PDOException $e){
        header("location:../500.php");
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
    <title>Welcome</title>
    <!-- Bootstrap Core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Theme CSS -->
    <link href="../assets/css/clean-blog.min.css" rel="stylesheet">
    <link href="../assets/css/extra.css" rel="stylesheet">

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
            </p>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="dashboard.php">Home</a>
                    </li>
                    <li>
                        <a href="createBlog.php"> Post</a>
                    </li>
                    <li>
                        <a href="viewAll.php">View All</a>
                    </li>
                    <li>
                        <a href="logout.php">Logout</a>
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
                        <h1>View all the blogs</h1>
                        <hr class="small">
                        <span class="subheading">You can comment too!</span>
                    </div>
                </div>
            </div>
        </div>
    </header>



    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <?php
                $results_per_page=2;
                $email=$_SESSION['email'];
                $sql=$conn->prepare("select * from blogs order by :order desc");
                $sql->bindParam(':order',$order,PDO::PARAM_INT );
                $sql->execute();
                $number_of_results= $sql->rowCount();
                $number_of_pages=ceil($number_of_results/$results_per_page);
                //echo $number_of_pages;
                if (!isset($_GET['page'])) {
                    $page=1;
                }
                else{
                    $page=$_GET['page'];
                }

                $this_page_first_result=($page-1)*$results_per_page;
                $sql=$conn->prepare("select * from blogs  order by :order desc limit :init,:end");
                $sql->bindParam(':order',$order,PDO::PARAM_INT );
                $sql->bindParam(':init',$this_page_first_result,PDO::PARAM_INT);
                $sql->bindParam(':end',$results_per_page,PDO::PARAM_INT);
                $sql->execute();
                while ($results = $sql->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="post-preview">
                    <a  href="../blogtheme/fullUserPost.php?blogId=<?php echo $results['id'];?>">
                        <h2 class="post-title">
                            <?php echo $results['title'];?>
                        </h2>
                        <h3 class="post-subtitle">
                            <?php echo $results['summary'];?>
                        </h3>
                    </a>
                    <?php 
                    try{
                        $sql=$conn->prepare("SELECT r.userName as u, c.commentText as c from comments c join users r on r.id=c.bloggerId where c.blogId=:id ");
                        $id= $results['id'];
                        $sql->bindParam(":id",$id);
                        $sql->execute();
                        while ($res = $sql->fetch(PDO::FETCH_ASSOC)) {
                        ?>                    
                        <div class="dialogbox">
                          <div class="body">
                            <span class="tip tip-up"></span>
                            <div class="message">
                              <span>
                                <?php
                                if(count($res)>0){
                                    echo $res['u']."<br>".$res['c'];
                                }
                                ?>
                            </span>
                            </div>
                          </div>
                        </div>
                        <?php   

                        }

                    }
                    catch(PDOException $e){
                        echo $sql->queryString . "<br>" . $e->getMessage();
                        //echo "bye";
                    }

                    ?>
                    <button type="button" class="show-comments">Show comments</button> 
                    <button type="button" class="hide-comments">Hide comments</button> 
                                    


                    <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" >
                        <br>
                        <textarea name="comment-box" id="comment" rows="2"  required="required"></textarea>
                        <input type="hidden" name="hidden-blog-id" value="<?php echo $results['id'];?>">
                        <input name="submit" type="submit" value="Comment"  />
                    </form>
                    <p class="post-meta">Posted by  
                    <?php 
                    try{
                        require_once('../fetchBloggerId.php');
                        $sql=$conn->prepare("SELECT * FROM users WHERE id=:id");
                        $sql->bindParam(":id",$bloggerId);
                        $sql->execute();
                        $re = $sql->fetch(PDO::FETCH_ASSOC);
                        echo $re['userName'];
                    }
                    catch(PDOException $e){
                       header("location: ../500.php");
                    }
                    ?>
                    </p>
                </div>
                <hr>

                <?php }
                if($page==1 || $page==$number_of_pages){
                    $count=2;   
                }
                else{
                    $count = 1;   
                }
                $prev=max(1,$page-1);
                if ($number_of_results>$results_per_page) {
                    echo '<a href="viewAll.php?page=' . $prev . '" class="page-no">Prev</a>';
                }
                
                $startPage = max(1, $page - $count);
                $endPage = min( $number_of_pages, $page + $count);
                for ($page=$startPage; $page <=$endPage ; $page++) { 
                    echo '<a href="viewAll.php?page=' . $page . '" class="page-no">' . $page . '</a>';

                }
                $next=min($page+1,$number_of_pages);
                if ($number_of_pages>$results_per_page) {
                    echo '<a href="viewAll.php?page=' . $next . '" class="page-no"> Next</a>';
                }
            ?>
            </div>
        </div>
    </div>

    <hr>

    <!-- Footer -->
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
                    <p class="copyright text-muted">Copyright &copy; Ashwarya Jethi</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="../assets/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="../assets//jqBootstrapValidation.js"></script>
    <script src="../ll/assets/js/contact_me.js"></script>

    <!-- Theme JavaScript -->
    <script src="../assets/js/clean-blog.min.js"></script>
    <script>
    $(document).ready(function(){
        $(".show-comments").click(function(){
            $(".dialogbox").slideDown();
        });
        $(".hide-comments").click(function(){
            $(".dialogbox").slideUp();
        });
    });
    </script>

</body>

</html>
