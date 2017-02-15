<?php
include_once('../bootstrap.php');
// Add html header layout
include_once('../layouts' . DIRECTORY_SEPARATOR . 'header.php');
?>
    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('../assets/img/home-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Welcome to your dashboard</h1>
                        <hr class="small">
                        <span class="subheading">Follow your passion, start blogging.</span>
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
                require_once '../fetchBloggerId.php';
                $sql=$conn->prepare("select * from blogs where bloggerId=:id order by :order desc");
                $sql->bindParam(':id',$bloggerId);
                $sql->bindParam(':order',$order,PDO::PARAM_INT );
                $sql->execute();
                $number_of_results= $sql->rowCount();
                if (0==$number_of_results) {
                    echo "No posts yet";
                }
                $number_of_pages=ceil($number_of_results/$results_per_page);
                if (!isset($_GET['page'])) {
                    $page=1;
                }
                else{
                    $page=$_GET['page'];
                }
                require_once '../fetchBloggerId.php';
                $this_page_first_result=($page-1)*$results_per_page;
                $sql=$conn->prepare("select * from blogs where bloggerId=:id order by :order desc limit :init,:end");
                $sql->bindParam(':id',$bloggerId);
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
                    <p class="post-meta">Posted by  <?php echo $_SESSION['username'];?></p>
                </div>
                <a href="editBlog.php?blogId=<?php echo $results['id'];?>" class="myButton" name="edit-button" >Edit</a>
                <a href="deleteBlog.php?blogId=<?php echo $results['id'];?>" class="myButton" name= "delete-button" onclick="return confirm('Please confirm deletion ');" >Delete</a>
                <hr>

                <?php }
                if($page==1 || $page==$number_of_pages){
                    $count=2;   
                }
                else{
                    $count = 1;   
                }
                $prev=max(1,$page-1);
                echo '<a href="dashboard.php?page=' . $prev . '" class="page-no">Prev</a>';
                $startPage = max(1, $page - $count);
                $endPage = min( $number_of_pages, $page + $count);
                for ($page=$startPage; $page <=$endPage ; $page++) { 
                    echo '<a href="dashboard.php?page=' . $page . '" class="page-no">' . $page . '</a>';

                }
                $next=min($page+1,$number_of_pages);
                echo '<a href="dashboard.php?page=' . $next . '" class="page-no"> Next</a>';
                ?>
                    
            </div>
        </div>
    </div>

    <hr>
<?php
    include_once('../layouts/footer.php');
?>
