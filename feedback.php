<?php
    /**
     * file name feedback.php
     * show all feedback (with pagination) from users
     * must need admin permission to visible feedback
     * and a form to get feedback.
     * save the feedback message in feedback table
    */
    session_start();
    require_once './admin/includes/connection.php';
    $conn = db_connect("read");
    include './config.php';
    $title = strtoupper($site_title)." :: Feedback";


     ///if an error occur store the feedback message and name to prevent refill the field
    $feedback = "";             /// initial feedback message variable
    $name = "";                 /// initial user name


    if(isset($_POST['submit_feedback'])){
        /// feedback message is submitted
        /// update variable
        $name = trim($_POST['name']);
        $feedback = trim($_POST['feedback']);

        /// check the name length
        if(strlen($name)<3){
            $errors[] = "Name must be at least 3 characters.";
        }
        if(strlen($name)>30){
            $errors[] = "Name must be less then 30 characters.";
        }

        /// check the message length
        if(strlen($feedback)<10){
            echo "here ".$feedback." is";
            $errors[] = "Message must be at least 10 characters.";
        }
        if(strlen($feedback)>500){
            $errors[] = "Message must be less then 500 characters.";
        }

        /// search any digit in name
        if (preg_match('/\d/', $name)){
            $errors[] = 'Name should not contain digit.';
        }

        /// search any special character
        $pattern = "/[-!$%^&*(){}<>[\]'" . '"|#@:;.,?+=_\/\~]/';
        $found = preg_match_all($pattern, $name, $matches);
        if ($found){
            $errors[] = 'Name should not contain any special character.';
        }

        if(empty($errors)){
            /// no error occur
            /// save the message to the database
            /// set the visibility = 0 (false) for admin approval
            $sql = "INSERT INTO feedback (name, message, visibility) VALUES (?, ?, ?)";
            $visibility = 0;
            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param('ssi', $name, $feedback, $visibility);
                if($stmt->execute()){
                    $success = "Feedback Save Successfully.";
                    /// clear the variable
                    $name = "";
                    $feedback = "";
                }else{
                    $errors[]= $stmt->error;
                }
            }

        }

    }


    /// pagination data
    $sql = "SELECT * FROM feedback WHERE visibility = 1";
    $result = $conn->query($sql);
    $totalRow = $result->num_rows;                  /// total number of message
    $limit = 12;                                    /// number of message per page
    $total_page = ceil($totalRow/$limit);     /// number of page require
    $page_num = 1;                                   /// initial the page number

    if (isset($_GET["page"])) {
        $page_num  = (int)$_GET["page"];
    }

    $start = ($page_num-1) * $limit;


    /// select feedback

    $sql = "SELECT *, DATE_FORMAT(date,'%m-%d-%Y') as c_date FROM feedback WHERE visibility = 1 ORDER BY id DESC LIMIT ?, ?";

    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('ii',$start,$limit);
        if($stmt->execute()){
            $result_review = $stmt->get_result();
        }else{
            die($stmt->error);
        }
    }else{
        die("Something Wrong.");
    }

include './includes/header.php';
?>
    <div class="container">

    <div class="row">
        <div class="col">

            <?php

            $num = 1;
            foreach($result_review as $row){

                ?>

                <div class="row pt-3">
                    <div class="col">
                        <div style="float: left; clear: left">
                            <img src="assets/images/avatar.png" style="width: 60px;" alt="avatar">

                        </div>
                        <div style="display: inline-block; margin-left: 15px">
                            <p style="margin-bottom: 5px;"><?= $row['name'];?></p>
                            <p><?= $row['c_date'];?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><?= $row['message'];?></p>
                    </div>

                </div>
                <?php
            }
            ?>

        </div>

    </div>

    <!--[ PAGINATION ]-->
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <!--[PREVIOUS PAGE]-->
                    <li class="page-item">
                        <a class="page-link btn <?php if($page_num<=1) echo 'disabled'; ?>" href="feedback.php?page=<?=($page_num-1);?>" aria-label="Previous" >
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>

                    <!--[ PREVIOUS 2 PAGE FROM CURRENT PAGE ]-->
                    <?php

                    for($i=2;$i>=1;$i--){
                        if($page_num-$i<1) continue;
                        ?>
                        <li class="page-item"><a class="page-link" href="feedback.php?page=<?php echo abs($page_num-$i);?>"><?php echo $page_num-$i;?></a></li>

                    <?php } ?>

                    <!--[ CURRENT PAGE ]-->
                    <li class="page-item" ><a class="page-link" href="feedback.php?page=<?php echo $page_num;?>" style="background: #ddd;"><?php echo $page_num;?></a></li>


                    <!--[ NEXT 2 PAGE FROM CURRENT PAGE ]-->
                    <?php
                    for($i=1;$i<3;$i++){
                        if($page_num+$i>$total_page) break;
                        ?>
                        <li class="page-item"><a class="page-link" href="feedback.php?page=<?php echo $page_num+$i;?>"><?php echo $page_num+$i;?></a></li>
                    <?php } ?>


                    <!--[NEXT PAGE]-->
                    <li class="page-item">

                        <a class="page-link btn <?php if($page_num>=$total_page) echo 'disabled'; ?>" href="feedback.php?page=<?=($page_num+1);?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-8 col-sm-12">
            <div style="background-color: #efefef; padding: 15px;">
                <?php
                if(isset($errors)){
                    echo '<ul class="error">';
                    foreach($errors as $error){
                        echo '<li>'.$error.'</li>';
                    }
                    echo '</ul>';
                }

                if(isset($success)){
                    echo '<p class="success">'.$success.'</p>';
                }

                ?>

                <form method="POST" action="" accept-charset="UTF-8">

                    <div class="form-group ">
                        <label for="name">Your Name</label>
                        <?php // set the previous data if an error occur ?>
                        <input class="form-control" id="name" name="name" type="text" value="<?= htmlentities($name);?>">
                    </div>

                    <div class="form-group ">
                        <label for="feedback">Your Feedback</label>
                        <?php // set the previous data if an error occur ?>
                        <textarea class="form-control" rows="5" name="feedback" cols="50"><?= htmlentities($feedback); ?></textarea>
                    </div>
                    <button type="submit" class="form-control" name="submit_feedback">Submit</button>
                </form>
            </div>
        </div>

        <div class="col-md-4 hidden-sm hidden-xs">
            <div class="" style="background-color: #efefef; padding: 15px;">
                <h2 class="" style="font-size: 17px;">You are invited to write a feedback about our service.</h2>
                <p style="font-size: 14px;">This feedback may help others.</p>
            </div>
        </div>
    </div>

<?php
include 'includes/footer.php';
?>