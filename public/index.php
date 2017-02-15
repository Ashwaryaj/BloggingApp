<?php
include_once('../bootstrap.php');

// Add html header layout
include_once('../layouts' . DIRECTORY_SEPARATOR . 'header.php');

?>
    <div class="intro-header">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                  <div class="intro-message">
                        <h1>Follow your passion</h1>
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
            <?php include_once('../layouts/listBlogs.php');?>
            </div>
        </div>
    </div>
    <hr>
<?php
    include_once('../layouts/footer.php');
?>