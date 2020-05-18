<?php
    require_once './includes/authenticate.php';
    require_once './includes/connection.php';		/// include mysqli_connect as $conn object
    $conn = db_connect("admin");				/// open mysqli connection as admin mode.

    /// total product
    $sql = "SELECT COUNT(*) as total FROM products";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_product = $row['total'];

    /// total invisible product
    $sql = "SELECT COUNT(*) as total FROM products WHERE visibility = 0";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_product_un = $row['total'];



    /// total menu
    $sql = "SELECT COUNT(*) as total FROM menu";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_menu = $row['total'];


    /// total news
    $sql = "SELECT COUNT(*) as total FROM news";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_news = $row['total'];


    /// total review
    $sql = "SELECT COUNT(*) as total FROM review";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_review = $row['total'];

    /// total invisible review
    $sql = "SELECT COUNT(*) as total FROM review WHERE visibility = 0";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_review_un = $row['total'];


    /// total feedback
    $sql = "SELECT COUNT(*) as total FROM feedback";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_feedback = $row['total'];

    /// total invisible feedback
    $sql = "SELECT COUNT(*) as total FROM feedback WHERE visibility = 0";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_feedback_un = $row['total'];


    /// total transaction form user details
    $sql = "SELECT COUNT(*) as total FROM transaction";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_transaction = $row['total'];


    include 'includes/admin_header.php';
    include 'includes/admin_nav.php';

?>
    <div class="col-sm-9 col-md-9">
       <!-- Products report -->
        <div class="row pt-3">
            <div class="col-12 border pt-3">
               <div class="row">
                   <div class="col-sm-6">
                       <h5>Product:</h5>
                   </div>
                   <div class="col-sm-6 text-right">
                       <a href="create_product.php">Create Product</a>
                   </div>
               </div>

                <p>Total Product <?= $total_product;?>. Unavailable Product
                    <?= ($total_product_un>0) ? '<span class="text-danger">'.$total_product_un.'</span>': $total_product_un;?></p>
            </div>
        </div>
        <!-- Menu report -->
        <div class="row pt-3">
            <div class="col-12 border pt-3">
                <div class="row">
                    <div class="col-sm-6">
                        <h5>Menu:</h5>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="create_menu.php">Create menu</a>
                    </div>
                </div>

                <p>Total Menu <?= $total_menu;?></p>
            </div>
        </div>

        <!-- News report -->
        <div class="row pt-3">
            <div class="col-12 border pt-3">
                <div class="row">
                    <div class="col-sm-6">
                        <h5>News:</h5>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="create_menu.php">Create News</a>
                    </div>
                </div>

                <p>Total News <?= $total_news;?></p>
            </div>
        </div>

        <!-- Review report -->
        <div class="row pt-3">
            <div class="col-12 border pt-3">
                <h5>Review:</h5>
                <p>Total Review <?= $total_review;?>. Unavailable Review
                    <?= ($total_review_un>0) ? '<span class="text-danger">'.$total_review_un.'</span>': $total_review_un;?></p>
            </div>
        </div>
        <!-- Feedback report -->
        <div class="row pt-3">
            <div class="col-12 border pt-3">
                <h5>Feedback:</h5>
                <p>Total Feedback <?= $total_feedback;?>. Unavailable Feedback
                    <?= ($total_feedback_un>0) ? '<span class="text-danger">'.$total_feedback_un.'</span>': $total_feedback_un;?></p>
            </div>
        </div>
        <!-- Transaction report -->
        <div class="row pt-3">
            <div class="col-12 border">
                <h5>Transaction:</h5>
                <p>Total Transaction <?= $total_transaction;?></p>
            </div>
        </div>

    </div>
<?php
include 'includes/footer.php';
?>