<?php 
    $results_per_page=3;
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