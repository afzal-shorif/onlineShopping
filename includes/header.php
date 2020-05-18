<?php
	/// mysql connection is added before include header
    /// select all the menu
    $sql_menu = "SELECT id, name FROM menu ORDER BY position ASC";
	$result_menu = $conn->query($sql_menu);                             /// $conn is mysql connection object
?>

<!DOCTYPE html>
<html lang="en">
	<head>
        <!-- Title is determine before include header -->
		<title><?= $title; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="assets/css/bootstrap/bootstrap.min.css" type="text/css">
		<!-- custom css -->
        <link rel="stylesheet" href="assets/css/style.css" type="text/css">
        <!-- offline font-awesome css -->
		<link rel="stylesheet" href="assets/css/font-awesome.min.css" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- to update cart price store all cart product price in javascript array -->
        <script>
            var single_product_price = [];              /// store cart products price
        </script>
	</head>
	<body class="" style="background-color: #fff;">
		<div class="container-fluid bg-dark">
			<div class="row pt-3 pb-3" id="header">
				<div class="container">
					<div class="row pl-3 pr-3">
						<div class="col-6 col-sm-2 col-md-2 p-1 bg-secondary" id="logo">
							<a href="index.php" style="text-decoration: none;"><h4 class="m-0 text-white"><?= strtoupper($site_title);?></h4></a>
						</div>
						<div class="col-6 col-sm-2 col-md-2" id="cart_xs" style="">
							<a href="shopping_cart.php">
								<i class="fa fa-shopping-cart icon"></i>
								<span class="badge badge-green favouritesCounter" id="count1">0</span>
							</a>
						</div>
						<div class="col-sm-8 col-md-8" id="search_bar">
							<div id="search_field_container">
								<div style="background-color: #fff; position: relative;">
									<form method="get" action="search.php">
										<input type="text" name="search" value="" placeholder="Enter Your Keyword" id="search_field">
										<div id="search_btn_container">
											<button type="submit" name="search_btn" id="search_btn" class="fa fa-search"></button>
										</div>																					
									</form>
								</div>	
							</div>									
						</div>
						<div class="col-6 col-sm-2 col-md-2" id="cart_sm">
							<a href="shopping_cart.php">
								<i class="fa fa-shopping-cart icon"></i>
								<span class="badge badge-green favouritesCounter" id="count2">0</span>
							</a>
						</div>
					</div>
					
					<div class="row pl-3 pr-3 pt-3">
						<div class="col p-0">
							<ul class="list-inline nav">
                                <!-- Home icon  -->
                                <li class="list-inline-item"><a href="index.php" class="currentPage"><i class="fa fa-fw fa-home"></i></a></li>
                                <!-- Display all menu -->
                                <?php foreach($result_menu as $menu){ ?>
                                    <li class="list-inline-item"><a href="products.php?menu_id=<?=$menu['id']?>"><?= ucwords($menu['name']);?></a></li>
								<?php }?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>