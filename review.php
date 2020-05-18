<?php
/**
 * file name review.php
 * must have with product id
 * display all review(with pagination) for a product
 * reviews are searched by product id
 * three slash (///) for documentation comment
 * two slash (//) for debug comment
 */

    session_start();
	require_once './admin/includes/connection.php';
	$conn = db_connect("read");

    include './config.php';
    $title = strtoupper($site_title)." :: Review";

	if(isset($_GET['id'])){
		$id = (int)$_GET['id'];
		
		/// pagination data
		$sql = "SELECT * FROM review WHERE product_id = ? AND visibility = 1";
		
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$id);
			if($stmt->execute()){
				$result = $stmt->get_result();
				$totalRow = $result->num_rows;
			}else{
				die($stmt->error); 
			}
		}else{
            die($stmt->error);
        }

		$limit = 8;
		$total_page = ceil($totalRow/$limit);
		$page_num = 1;

		if (isset($_GET["page"])) {  
		  $page_num  = (int)$_GET["page"];  
		}
		 
		$start = ($page_num-1) * $limit;

		/// get product title
		$sql = "SELECT title from products WHERE  product_id = ?";

        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('i',$id);
            if($stmt->execute()){
                $result_product_title = $stmt->get_result();
                if($result_product_title->num_rows <= 0) header('Location: 404.php');
                $product_title = $result_product_title->fetch_assoc();
            }else{
                die($stmt->error);
            }
        }else{
            die("Something Wrong.");
        }




		/// select review

        $sql = "SELECT *, DATE_FORMAT(date,'%m-%d-%Y') as c_date FROM review WHERE product_id = ? AND visibility = 1 ORDER BY id DESC LIMIT ?, ?";
		
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('iii',$id,$start,$limit);
			if($stmt->execute()){
				$result_review = $stmt->get_result();
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
            <div class="row mt-4">
                <div class="col-12">
                    <a href="single_product.php?id=<?= $id;?>"><h4><?= $product_title['title'];?></h4></a>
                </div>
            </div>
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
							  <a class="page-link btn <?php if($page_num<=1) echo 'disabled'; ?>" href="review.php?id=<?= $id?>&page=<?=($page_num-1);?>" aria-label="Previous" >
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							  </a>
							</li>

							<!--[ PREVIOUS 2 PAGE FROM CURRENT PAGE ]-->
							<?php

								for($i=2;$i>=1;$i--){
									if($page_num-$i<1) continue;
							?>
								<li class="page-item"><a class="page-link" href="review.php?id=<?= $id?>&page=<?php echo abs($page_num-$i);?>"><?php echo $page_num-$i;?></a></li>

							<?php } ?>

							<!--[ CURRENT PAGE ]-->
							<li class="page-item" ><a class="page-link" href="review.php?id=<?= $id?>&page=<?php echo $page_num;?>" style="background: #ddd;"><?php echo $page_num;?></a></li>


							<!--[ NEXT 2 PAGE FROM CURRENT PAGE ]-->
							<?php
								for($i=1;$i<3;$i++){
									if($page_num+$i>$total_page) break;
							?>
								<li class="page-item"><a class="page-link" href="review.php?id=<?= $id?>&page=<?php echo $page_num+$i;?>"><?php echo $page_num+$i;?></a></li>
							<?php } ?>


							<!--[NEXT PAGE]-->
							<li class="page-item">

							  <a class="page-link btn <?php if($page_num>=$total_page) echo 'disabled'; ?>" href="review.php?id=<?= $id?>&page=<?=($page_num+1);?>" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							  </a>
							</li>

						</ul>
					</nav>
				</div>
			</div>


	<?php
		include 'includes/footer.php';
	?>