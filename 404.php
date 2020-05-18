<?php
    /**
     *  file name 404.php
     *  redirect when user get an invalid url
     *  database connection need for header
    */

    session_start();
    require_once './admin/includes/connection.php';
    $conn = db_connect("read");
    include './config.php';
    $title = strtoupper($site_title)." :: 404 page not found";

    include './includes/header.php';
?>

    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col text-center">
                <!-- RESPONSIVE TEXT SIZE -->
                <h1 style="font-size: 15vw;">404</h1>
                <h5>Page Not Found</h5>
            </div>
        </div>

<?php
include './includes/footer.php';
?>