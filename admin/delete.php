<?php
	require_once './includes/authenticate.php';


	require_once 'includes/connection.php';
	$conn = db_connect("read");
	
	if(isset($_GET['id']) && isset($_GET['type'])){
		$redirect = "";
		
		if($_GET['type'] == "news"){
			$sql = "DELETE FROM news WHERE id = ?";
			$redirect = "news.php";
		}else if($_GET['type'] == "menu"){
			$sql = "DELETE FROM menu WHERE id = ?";
			$redirect = "menu.php";
			
		}else if($_GET['type'] == "product"){
			$sql = "DELETE FROM products WHERE product_id = ?";
			$redirect = "products.php";
		}else if($_GET['type'] == "feedback"){
			$redirect = "feedback.php";
			if(isset($_GET['action'])){
				if($_GET['action'] == 't'){
					/// action t mean review is hide
					/// now we have to visible
					/// set the value true
					$sql = "UPDATE feedback SET visibility = 1 WHERE id = ?";
				}
				if($_GET['action'] == 'f'){
					/// action f mean review is visible
					/// now we need to hide
					/// set the value false
					$sql = "UPDATE feedback SET visibility = 0 WHERE id = ?";
				}
			}else{
				$sql = "DELETE FROM feedback WHERE id = ?";
			}

		}else if($_GET['type'] == "review"){
			$redirect = "product_review.php";
			if(isset($_GET['action'])){
				if($_GET['action'] == 't'){
					/// action t mean review is hide
					/// now we have to visible
					/// set the value true
					$sql = "UPDATE review SET visibility = 1 WHERE id = ?";
				}
				if($_GET['action'] == 'f'){
					/// action f mean review is visible
					/// now we need to hide
					/// set the value false
					$sql = "UPDATE review SET visibility = 0 WHERE id = ?";
				}

			}else{
				$sql = "DELETE FROM review WHERE id = ?";
			}
		}
		
		if(strlen($redirect)>0){
			if($stmt = $conn->prepare($sql)){
				$stmt->bind_param('i',$_GET['id']);
				$stmt->execute();
				header("Location: $redirect");
			}	
		}
		
	}else{
		header("Location: 404.html");	
	}

?>
