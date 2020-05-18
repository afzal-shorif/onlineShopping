<?php
/**
 * file name remove.php
 * delete a product from user cart list
 * and redirect shopping_cart.php
 * cart list store in tmp table with unique session_id (php session id)
 */

    session_start();
    require_once './admin/includes/connection.php';
    $conn = db_connect("read");

	if(isset($_GET['product_id'])){
		
		$redirect = "shopping_cart.php";

		$sql = "DELETE FROM tmp WHERE session_id = ? AND product_id = ?";
        $session_id = session_id();
		if($stmt = $conn->prepare($sql)){
		    $stmt->bind_param('si',$session_id, $_GET['product_id']);
		    if($stmt->execute()) {
                header("Location: $redirect");
            }else{
		        die($stmt->error);
            }
        }else{
		    die($stmt->error);
        }
	}else{
		header('Location: 404.php');
	}
