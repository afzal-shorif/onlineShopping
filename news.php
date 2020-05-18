<?php
/**
 * file name news.php
 * display a single news using news id
 * news id must be valid
 * if invalid news id, redirect 404.php page
 */

session_start();
require_once './admin/includes/connection.php';
$conn = db_connect("read");

$today = date('Y-m-d');
include './config.php';
$title = strtoupper($site_title)." :: News";


if(isset($_GET['id'])){
    $id = $_GET['id'];

    $sql = "SELECT * FROM news WHERE id = ? AND to_show >= ?";

    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('is',$id,$today);
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result ->num_rows <= 0) header('Location: 404.php');
            $result = $result->fetch_assoc();
        }else{
            die($stmt->error);
        }
    }else{
        die("Something Wrong.");
    }

}else{
    header('Location: 404.php');
}

include './includes/header.php';
?>
    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-12 col-sm-12 col-md-6">
                <h3><?php if(isset($result['title'])) echo $result['title'];?></h3>
                <P class="mt-4"><?php if(isset($result['description'])) echo $result['description'];?></P>
            </div>
            <div class="col-12 col-sm-12 col-md-6">
                <img class="d-block w-100" src="assets/images/news/<?php if(isset($result['image'])) echo $result['image'];?>" alt="<?php if(isset($result['title'])) echo $result['title'];?>">
            </div>
        </div>
<?php
include './includes/footer.php';
?>
