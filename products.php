<?php
/**
 * file name products.php
 * display all product under a menu
 * menu id must be valid
 */
    session_start();
	require_once './admin/includes/connection.php';
	$conn = db_connect("read");

	include './config.php';			/// include the site name
	$title = strtoupper($site_title);
	
	if(isset($_GET['menu_id'])){
		$id = (int)$_GET['menu_id'];
		
		/// pagination data
		$sql = "SELECT * FROM products WHERE menu_id = ?";
		
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$id);
			if($stmt->execute()){
				$result = $stmt->get_result();
				$totalRow = $result->num_rows;
			}else{
				die($stmt->error); 
			}
		}else{
			die("Something Wrong.");
		}

		$limit = 12;
		$total_page = ceil($totalRow/$limit);
		$page_num = 1;

		if (isset($_GET["page"])) {  
		  $page_num  = (int)$_GET["page"];  
		}
		 
		$start = ($page_num-1) * $limit;
	
		/// select menu name
		$sql = "SELECT name FROM menu WHERE id = ?";
		$link = "";
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$id);
			if($stmt->execute()){
				$result = $stmt->get_result();
				if($result->num_rows <= 0) header('Location: 404.php');
				$row = $result->fetch_assoc();
                $link = ucfirst($row['name']);
				$title = strtoupper($site_title)." :: ".$link;
			}else{
				echo $stmt->error;
			}
		}else{
			die("Something Wrong.");
		}
		
		
		/// select product data
		
		$sql = "SELECT * FROM products WHERE menu_id = ? ORDER BY product_id DESC LIMIT ?, ?";
		
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('iii',$id,$start,$limit);
			if($stmt->execute()){
				$result_product = $stmt->get_result();
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
			
			<div class="row pt-3">
				<div class="col">
					<ul class="list-inline">
						<li class="list-inline-item"><a href="index.php" class=""><i class="fa fa-fw fa-home"></i></a></li>							
						<li class="list-inline-item"><i class="fa fa-long-arrow-right"></i></li>							
						<li class="list-inline-item"><?= $link;?></li>							
					</ul>
				</div>
			</div>
			
			<div class="row">
				<?php 					
					$num = 1;
					foreach($result_product as $row){			
				?>
				<li class="col-sm-3 col-md-3 col-lg-3" style="list-style: none;">
					<div style="margin-bottom: 15px;">
						<div class="card">
						  <img class="card-img-top" src="assets/images/products/<?= $row['picture'];?>" alt="Card image cap">
						  <div class="card-body">
							<h5 class="card-title"><?= $row['title'];?></h5>
							<p class="card-text" title="<?= $row['description'];?>">
								<a href="single_product.php?id=<?=$row['product_id'];?>">
									<?= substr($row['description'],0,50)."...";?>
								</a>
							</p>
							<p class="card-text">Regular Price BDT  <?= $row['price'];?></p>
						  </div>
						  <div class="card-body">
							
							<button type="button" class="form-control list_btn addCartBTN" id="<?= $row['product_id'];?>">
								Add To Cart<i class="fa fa-circle-o-notch fa-spin" style="font-size: 16px; display: none;"></i>
							</button>
						  </div>
						</div>
					</div>
				</li>
				<?php 					
					}
				?>
				
			</div>
			
			<!--[ PAGINATION ]-->
			<div class="row mt-3">
				<div class="col">							
					<nav aria-label="Page navigation example">
						<ul class="pagination justify-content-center">						
							<!--[PREVIOUS PAGE]-->
							<li class="page-item">
							  <a class="page-link btn <?php if($page_num<=1) echo 'disabled'; ?>" href="products.php?menu_id=<?= $id?>&page=<?=($page_num-1);?>" aria-label="Previous" >
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							  </a>
							</li>
							
							<!--[ PREVIOUS 2 PAGE FROM CURRENT PAGE ]-->
							<?php
								
								for($i=2;$i>=1;$i--){											
									if($page_num-$i<1) continue;
							?>
								<li class="page-item"><a class="page-link" href="products.php?menu_id=<?= $id?>&page=<?php echo abs($page_num-$i);?>"><?php echo $page_num-$i;?></a></li>
							
							<?php } ?>
							
							<!--[ CURRENT PAGE ]-->
							<li class="page-item" ><a class="page-link" href="products.php?menu_id=<?= $id?>&page=<?php echo $page_num;?>" style="background: #ddd;"><?php echo $page_num;?></a></li>
							
							
							<!--[ NEXT 2 PAGE FROM CURRENT PAGE ]-->									
							<?php
								for($i=1;$i<3;$i++){											
									if($page_num+$i>$total_page) break;											
							?>
								<li class="page-item"><a class="page-link" href="products.php?menu_id=<?= $id?>&page=<?php echo $page_num+$i;?>"><?php echo $page_num+$i;?></a></li>
							<?php } ?>
							
							
							<!--[NEXT PAGE]-->
							<li class="page-item">
							
							  <a class="page-link btn <?php if($page_num>=$total_page) echo 'disabled'; ?>" href="products.php?menu_id=<?= $id?>&page=<?=($page_num+1);?>" aria-label="Next">
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