<?php
/**
 * file name index.php
 * home page for user
 * display all header(logo, search bar, menu etc), news feed, latest 4 product, footer
 * show available news only
 */
    /// display error report
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	/// start session for identify user
    /// session id is

	session_start();

	require_once './admin/includes/connection.php';             /// include mysql database connection
	$conn = db_connect("read");                        /// make a database connection as read mode

    $today = date('Y-m-d');                              /// date format for today

    /// get the slideshow images
    ///  where offer is available (visibility is 1)
	$sql = "SELECT id, image FROM news WHERE visibility = 1 AND to_show >='$today' ORDER BY id DESC";
	$result_news = $conn->query($sql);

	/// get the latest 4 product
	$sql = "SELECT * FROM products ORDER BY product_id DESC LIMIT 0,4";
	$result_product = $conn->query($sql);
	include './config.php';			/// include the site name

	$title = strtoupper($site_title);          /// default title for this document
	
	include './includes/header.php';
?>		
		<div class="container">

            <!-- news feed  -->
			<div class="row pt-5">

                <!-- container for Display slideshow -->
                <div class="col-sm-8">
                    <!-- slideshow carousel indicators-->
					<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
					  <ol class="carousel-indicators">
                          <!-- add active class to the first image  carousel indicators-->
                          <?php
							$active = "active";
							for($i=0;$i<$result_news->num_rows;$i++){
						?>
						<li data-target="#carouselExampleIndicators" data-slide-to="<?= $i;?>" class="<?=$active;?>"></li>
							<?php
							$active = "";
							}
							?>
					  </ol>

                      <!-- slideshow image contaner-->
                        <!-- To visible first image only add active class to the first image container  -->
					  <div class="carousel-inner">
						<?php 
							$active = "active";             /// active the first image
							foreach($result_news as $row){
								
						?>
                        <!-- initially active the first image -->
                        <!-- add active class for first image -->
                        <div class="carousel-item <?= $active;?>">
                            <a href="news.php?id=<?= $row['id'];?>"><img class="d-block w-100" src="assets/images/news/<?= $row['image'];?>" alt="<?php if(isset($row['title']))echo $row['title'];?>>"></a>
						</div>
						<?php 
							$active = "";           /// clear active variable
							}
						?>
					  </div>

					  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					  </a>
					  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					  </a>
					</div>					
				</div>
                <!-- end slideshow -->

                <!-- contact info -->
				<div class="col-sm-4" id="info_pic">
					<div class="row">
						<div class="col">
							<img class="d-block w-100" src="assets/images/info.jpg" alt="contact info">
						</div>
					</div>
					<div class="row">
						<div class="col pt-3">
							<img class="d-block w-100" src="assets/images/info_2.jpg" alt="contact info">
						</div>
					</div>
				</div>


			</div>
            <!-- end news feed  -->

			<div class="row p-3">
				<div class="col border border-top-0 border-left-0 border-right-0 border-danger">
					<div class="row">
						<div class="col"><h4>Collections</h4></div>
						<!--
                        <div class="col text-right">
							<button class="mr-1 btn">
								<i class='fa fa-angle-left'></i>
							</button>
							<button class="btn">
								<i class="fa fa-angle-right"></i>
							</button>
						</div>
                        -->
					</div>
					
				</div>
			</div>
            <!-- Display latest products -->
			<div class="row">
				<?php 					
					foreach($result_product as $row){			
				?>
				
				<div class="col-sm-12 col-md-3 col-lg-3">
					<div class="card">					  
					  <img class="card-img-top" src="assets/images/products/<?= $row['picture'];?>" alt="<?= $row['title'];?>">
					  <div class="card-body">
						<h5 class="card-title"><?= $row['title'];?></h5>
						<p class="card-text" title="<?= $row['description'];?>"><a href="single_product.php?id=<?=$row['product_id'];?>"><?= substr($row['description'],0,50)."...";?></a></p>
						<p class="card-text">Regular Price BDT  <?= $row['price'];?></p>
					  </div>
					  <div class="card-body">
						<button class="form-control addCartBTN list_btn" id="<?=$row['product_id'];?>">
							Add To Cart <i class="fa fa-circle-o-notch fa-spin" style="font-size: 16px; display: none;"></i>
						</button>
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