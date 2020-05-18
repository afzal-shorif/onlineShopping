<?php 
	$currentPage = basename($_SERVER['SCRIPT_FILENAME']);
	$title = basename($_SERVER['SCRIPT_FILENAME'], '.php');
	if($title == 'index') $title = 'Dashboard';
include "../config.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title> <?= strtoupper($site_title)." :: ".ucfirst($title);?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="../assets/css/style.css" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		
	
	</head>
	<body class="">
		<div class="container mb-5">
			<div class="row pt-3 pb-3 bg-dark text-white">			
				<div class="col-12 col-sm-6">
                    <a href="../index.php" style="text-decoration: none"><h4 class="m-0 text-white"><?= strtoupper($site_title);?></h4></a>
				</div>
				<div class="col-12 col-sm-6" style="text-align: right;">
					<style rel="stylesheet">
						.profileBTN{
							border-radius: 5px !important;							
							border: 2px solid #049d2a;							
						}
					</style>
					
					<?php
                    $name =  $_SESSION['user_name'];
                    echo $name;
					?>
					<div class="btn-group">
					  <button type="button" class="btn profileBTN bg-dark text-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php echo ucfirst($name[0]); ?>
					  </button>
					  <div class="dropdown-menu dropdown-menu-right">
						<a href="logout.php" class="dropdown-item" type="button">Log Out</a>
					  </div>
					</div>
				</div>				
			</div>