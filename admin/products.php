<?php
    require_once './includes/authenticate.php';
    require_once './includes/connection.php';		/// include mysqli_connect as $conn object
	$conn = db_connect("admin");				/// open mysqli connection as admin mode.
	

	$sql = "SELECT * FROM products";
	$result = $conn->query($sql);

	$totalRow = $result->num_rows;
	
	$limit = 8;
	$total_page = ceil($totalRow/$limit);
	$page_num = 1;
	
    if (isset($_GET["page"])) {  
      $page_num  = (int)$_GET["page"];  
    }
     
    $start = ($page_num-1) * $limit;
		
	
	$sql = "SELECT * FROM products ORDER BY product_id DESC LIMIT ?, ?";
	
	if($stmt = $conn->prepare($sql)){
		$stmt->bind_param('ii', $start, $limit);
		$stmt->execute();
		$result = $stmt->get_result();
	}else{
		echo "Something wrong happend.";
		exit();
	}
	
	include 'includes/admin_header.php';
	include 'includes/admin_nav.php';
	
	
?>
				<div class="col-sm-9 col-md-9">
					
					<div class="row pt-3">
						<div class="col-12 text-right">							
							<a href="create_product.php">Add Product</a>
						</div>
					</div>
					
					<div class="row pt-3">
						<div class="col-12">
						
							<table class="table">
							  <thead>
								<tr>
								  <th scope="col">#</th>
								  <th scope="col">Name</th>
								  <th scope="col">Category</th>
								  <th scope="col">Visible</th>
								  <th scope="col">Price</th>
								  <th scope="col">Action</th>
								</tr>
							  </thead>
							  <tbody>
								<?php
                                    $num = (($page_num-1)*$limit)+1;
									foreach($result as $row){															
										$row['visibility']? $visible = "Yes":$visible = "No";
										
										/// query for category 
										
										$sql = "SELECT name FROM menu WHERE id = ?";
										if($stmt = $conn->prepare($sql)){
											$stmt->bind_param('i', $row['menu_id']);
											$stmt->execute();
											$menu = $stmt->get_result();
											$category = $menu->fetch_assoc();
										}else{
											echo "Something wrong happend.";
											exit();
										}
										
								?>
								<tr>
								  <th scope="row"><?= $num++; ?></th>
								  <td><?= ucwords($row['title']); ?></td>
								  <td><?= $category['name']; ?></td>
								  <td><?= $visible; ?></td>
								  <td><?= $row['price']; ?></td>
								  <td><a href="create_product.php?id=<?= $row['product_id'];?>"><i class="fa fa-edit" style="margin-right: 10px;"></i></a>
                                      <!--
                                      <a href="delete.php?type=product&id=<?= $row['product_id'];?>"><i class="fa fa-trash"></i></a>
                                      -->
                                  </td>
								</tr>
								
									<?php } ?>
								
							  </tbody>
							</table>
						</div>
					</div>

					<!--[ PAGINATION ]-->
					<div class="row mt-3">
						<div class="col">							
							<nav aria-label="Page navigation example">
								<ul class="pagination justify-content-center">						
									<!--[PREVIOUS PAGE]-->
									<li class="page-item">
									  <a class="page-link btn <?php if($page_num<=1) echo 'disabled'; ?>" href="products.php?page=<?=($page_num-1);?>" aria-label="Previous">
										<span aria-hidden="true">&laquo;</span>
										<span class="sr-only">Previous</span>
									  </a>
									</li>
									
									<!--[ PREVIOUS 2 PAGE FROM CURRENT PAGE ]-->
									<?php
										
										for($i=2;$i>=1;$i--){											
											if($page_num-$i<1) continue;
									?>
										<li class="page-item"><a class="page-link" href="products.php?page=<?php echo abs($page_num-$i);?>"><?php echo $page_num-$i;?></a></li>
									
									<?php } ?>
									
									<!--[ CURRENT PAGE ]-->
									<li class="page-item" ><a class="page-link" href="products.php?page=<?php echo $page_num;?>" style="background: #ddd;"><?php echo $page_num;?></a></li>
									
									
									<!--[ NEXT 2 PAGE FROM CURRENT PAGE ]-->									
									<?php
										for($i=1;$i<3;$i++){											
											if($page_num+$i>$total_page) break;											
									?>
										<li class="page-item"><a class="page-link" href="products.php?page=<?php echo $page_num+$i;?>"><?php echo $page_num+$i;?></a></li>
									<?php } ?>
									
									
									<!--[NEXT PAGE]-->
									<li class="page-item">
									
									  <a class="page-link btn <?php if($page_num>=$total_page) echo 'disabled'; ?>" href="products.php?page=<?= ($page_num+1);?>" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
										<span class="sr-only">Next</span>
									  </a>									  
									</li>
									
								</ul>
							</nav>
						</div>
					</div>
				</div>
    <?php
        include 'includes/footer.php';
    ?>