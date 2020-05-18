<?php
/**
 * file name single_product.php
 * display a single product with product id
 * and a form to get product review
 */

	session_start();
	require_once './admin/includes/connection.php';
	$conn = db_connect("read");
    include './config.php';
    $title = strtoupper($site_title);
	$today = date('m-d-Y');

	$review = "";
	$name = "";
	if(isset($_POST['review_submit'])){
		$name = trim($_POST['name']);
		$review = trim($_POST['review']);
		
		
		if(strlen($name)<3){
			$errors[] = "Name must be at least 3 characters.";
		}
		if(strlen($name)>30){
			$errors[] = "Name must be less then 30 characters.";
		}
		
		if(strlen($review)<10){
			$errors[] = "Message must be at least 10 characters.";
		}
		if(strlen($review)>500){
			$errors[] = "Message must be less then 500 characters.";
		}
		
		/// search any digit
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
			$id = (int) $_GET['id'];
			$sql = "INSERT INTO review (product_id, name, message, visibility) VALUES (?, ?, ?, ?)";
			$visibility = 0;
			if($stmt = $conn->prepare($sql)){
				$stmt->bind_param('issi', $id, $name, $review, $visibility);
				if($stmt->execute()){
					$success = "Review Save Successfully.";
					$name = "";
					$review = "";
				}else{
					$errors[]= $stmt->error;
				}
			}
		
		}

	}
	
	
	if(isset($_GET['id'])){
		$sql = "SELECT *, DATE_FORMAT(date,'%m.%d.%y') as c_date FROM products WHERE product_id = ?";
		$id = (int)$_GET['id'];
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$id);
			if($stmt->execute()){
				$result = $stmt->get_result();
                if($result->num_rows <= 0) header('Location: 404.php');
				$row = $result->fetch_assoc();
				$title =  strtoupper($site_title)." :: ".ucfirst($row['title']);
				$link = ucfirst($row['title']);
				
				$sql = "SELECT name,id FROM menu WHERE id = ?";
				if($stmt = $conn->prepare($sql)){
					$stmt->bind_param('i',$row['menu_id']);
					if($stmt->execute()){
						$result = $stmt->get_result();
                        if($result->num_rows <= 0) header('Location: 404.php');
						$menu_row = $result->fetch_assoc();
						$menu_name = $menu_row['name'];						
						$menu_id = $menu_row['id'];						
					}
				}
				
			}else{
				echo $stmt->error;
			}
		}else{
			die("Something Wrong.");
		}
		
	}else{
		header('Location: 404.php');
	}
	
	
	$sql = "SELECT * FROM products WHERE menu_id = $menu_id ORDER BY product_id DESC LIMIT 0, 4";
	$result_product = $conn->query($sql);
	
	include './includes/header.php';
?>
		
		<div class="container">
			<div class="row pt-3">
				<div class="col">
					<ul class="list-inline">
						<li class="list-inline-item"><a href="index.php" class=""><i class="fa fa-fw fa-home"></i></a></li>							
						<li class="list-inline-item"><i class="fa fa-long-arrow-right"></i></li>							
						<li class="list-inline-item"><a href="products.php?menu_id=<?= $menu_id;?>" class=""><?= ucfirst($menu_name);?></a></li>
						<li class="list-inline-item"><i class="fa fa-long-arrow-right"></i></li>							
						<li class="list-inline-item"><?= ucfirst($link);?></li>						
					</ul>
				</div>
			</div>
			
			<div class="row pt-5">
				<div class="col-md-6 col-sm-6">
					<div class="border" style="max-width: 494px; max-height: 494px;">
						<img class="card-img-top" src="assets/images/products/<?= $row['picture'];?>" alt="Card image cap">
					</div>					
				</div>
				<div class="col-md-6 col-sm-6">
					<h4><?= $row['title']?></h4>
					<p class="pt-3">Product Id: <?= $row['product_id'].".".$row['c_date'];?></p>
					<p>Regular Price BDT  <?= $row['price'];?></p>
					<h5>Quick Overview</h5>
					<p><?= $row['description'];?></p>
					<div class="pt-3">
						<form class="">
							<div class="row">
								<div class="col-6 col-sm-6 col-md-8">
									<input type="number" min="1" max="10" value="1" class="form-control" style="width: 100%;" id="quantity_input">
								</div>						
								<div class="col-6 col-sm-6 col-md-4 pl-0">
									<button type="button" class="form-control list_btn" id="<?= $row['product_id'];?>" style="width: 100%;">
										Add To Cart<i class="fa fa-circle-o-notch fa-spin" style="font-size: 16px; display: none;"></i>
									</button>
									
								</div>
							</div>							
						</form>	
					</div>
					<p class="mt-3"><a href="review.php?id=<?= $row['product_id'];?>">Check Review ?</a></p>
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
								<input class="form-control" id="name" name="name" type="text" value="<?= htmlentities($name);?>">
							</div>

							<div class="form-group ">
								<label for="review">Your Review</label>
								<textarea class="form-control" rows="5" name="review" cols="50"><?= htmlentities($review); ?></textarea>
							</div>
							<button type="submit" class="form-control" name="review_submit">Submit</button>
						</form>
					</div>
				</div>

				<div class="col-md-4 hidden-sm hidden-xs">
					<div class="" style="background-color: #efefef; padding: 15px;">
						<h2 class="" style="font-size: 17px;">You are invited to write a review about this product.</h2>
						<p style="font-size: 14px;">This review may help others to choose a perfect product.</p>
					</div>
				</div>
			</div>
			<div class="row mt-5 p-3">
					<div class="col border border-top-0 border-left-0 border-right-0 border-danger">
						<h4 style="">Related Products</h4>
					</div>
				</div>
			<div class="row mt-3">
				<?php 					
					foreach($result_product as $row){			
				?>
				
				<div class="col-sm-12 col-md-3 col-lg-3">
					<div class="card">					  
					  <img class="card-img-top" src="assets/images/products/<?= $row['picture'];?>" alt="Card image cap">
					  <div class="card-body">
						<h5 class="card-title"><?= $row['title'];?></h5>
						<p class="card-text" title="<?= $row['description'];?>"><a href="single_product.php?id=<?=$row['product_id'];?>"><?= substr($row['description'],0,50)."...";?></a></p>
						<p class="card-text">Regular Price BDT  <?= $row['price'];?></p>
					  </div>
					  <div class="card-body">
						<button class="form-control addCartBTN">Add To Cart</button>
					  </div>
					</div>					
				</div>
				<?php 
					}
				?>
			</div>
	<?php 
		include 'includes/footer.php';
	?>