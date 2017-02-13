<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require_once('blogtheme/dbconnect.php')
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Home</title>
    <!-- Bootstrap Core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/landing-page.css" rel="stylesheet">

    <link href="assets/css/extra.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
        <div class="container topnav">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand topnav" href="#">Blogger</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="login.php">Sign in</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>


    <!-- Header -->
    <a name="about"></a>
    <div class="intro-header">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                 z   <div class="intro-message">
                        z<h1>Follow your passion</h1>
                        <h3>Create your own blog</h3>
                        <hr class="intro-divider">
                        <ul class="list-inline">
                            <li>
                                <a href="registration.php" class="btn btn-default btn-lg">Let's start</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <?php 
            $results_per_page=1;
            $Status="published";
            $order=1;
            $sql=$conn->prepare("select * from blogs where status=:blogStatus order by :order desc");
            $sql->bindParam(':blogStatus',$Status);
            $sql->bindParam(':order',$order,PDO::PARAM_INT );
            $sql->execute();
            $number_of_results=$sql->rowCount();
            $number_of_pages=ceil($number_of_results/$results_per_page);

            if (!isset($_GET['page'])) {
                $page=1;
            }
            else{
                $page=$_GET['page'];
            }

            $this_page_first_result=($page-1)*$results_per_page;
            $Status="Published";
            $order=1;
            $sql=$conn->prepare("select * from blogs where status=:blogStatus order by :order desc limit :init , :end");
            $sql->bindParam(':blogStatus',$Status);
            $sql->bindParam(':order',$order,PDO::PARAM_INT );
            $sql->bindParam(':init',$this_page_first_result,PDO::PARAM_INT);
            $sql->bindParam(':end',$results_per_page,PDO::PARAM_INT);
            $sql->execute();
            while ($results = $sql->fetch(PDO::FETCH_ASSOC)) {
            ?>
             <div class="post-preview">
                    <a href="fullBlog.php?blogId=<?php echo $results['id'];?>">
                        <h2 class="post-title heading">
                            <?php echo $results['title'];?>
                        </h2>
                        <h3 class="post-subtitle heading">
                            <?php echo $results['summary'];?>
                        </h3>
                    </a>
                    <p class="post-meta">Posted by  
                        <?php 
                        $bloggerId= $results['bloggerId'];
                        $sql=$conn->prepare("SELECT username FROM users WHERE id=:id");
                        $sql->bindParam(":id",$bloggerId);
                        $sql->execute();
                        $res = $sql->fetch(PDO::FETCH_ASSOC);
                        echo $res['username'];
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
            echo '<a href="firstpage.php?page=' . $prev . '" class="page-no">Prev</a>';
            $startPage = max(1, $page - $count);
            $endPage = min( $number_of_pages, $page + $count);
            for ($page=$startPage; $page <=$endPage ; $page++) { 
                echo '<a href="firstpage.php?page=' . $page . '" class="page-no">' . $page . '</a>';

            }
            $next=min($page+1,$number_of_pages);
            echo '<a href="firstpage.php?page=' . $next . '" class="page-no"> Next</a>';
            ?>
 
            </div>
        </div>
    </div>
    <hr>


	<a  name="contact"></a>
    <div class="banner">

        <div class="container">

            <div class="row">
                <div class="col-lg-6">
                    <h2>Connect to us:</h2>
                </div>
                <div class="col-lg-6">
                    <ul class="list-inline banner-social-buttons">
                        <li>
                            <a href="#" class="btn btn-default btn-lg"><i class="fa fa-linkedin fa-fw"></i> <span class="network-name">Linkedin</span></a>
                        </li>
                       
                        <li>
                            <a href="#" class="btn btn-default btn-lg"><i class="fa fa-facebook fa-fw"></i> <span class="network-name">Facebook</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="list-inline">
                        <li>
                            <a href="login.php">Login</a>
                        </li>
                    </ul>
                    <p class="copyright text-muted small">Copyright &copy; Ashwarya Jethi 2017. All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>
     <!-- jQuery -->
    <script src="assets/js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>

</body>

</html>
