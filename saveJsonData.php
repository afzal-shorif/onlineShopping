<?php
/**
 * file name saveJsonData.php
 * use for ajax request in json format
 * require API class (API.php in include directory)
 * API class contain all database action
 * get request (with session_id) to get the number of product (unique) in the cart list for this user
 * post request (with session_id) to add product in the cart and get back the number of product (unique) has on cart list
 * put request (with session_id) to update a product (increment or decrement the quantity) and get the number of product (unique) has on cart list
 * to delete product from cart list use remove.php
 * three slash (///) for documentation comment
 * two slash (//) for debug comment
 */
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'nothing'){
		/*
		require_once './admin/includes/connection.php';   
		$conn = db_connect("read");
		
		//header("Content-Type: application/json; charset=UTF-8")
		
		$data = json_decode(file_get_contents('php://input'), true);
			
		$sql = "INSERT INTO tmp (session_id, product_id, quantity) VALUES (?, ?, ?)";
			
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('sii', $data['user_id'], $data['product_id'], $data['quantity']);
			if($stmt->execute()){
			
				$sql = "SELECT * FROM tmp WHERE session_id = ?";
				
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('s',$data['user_id']);
				$stmt->execute();
				
				$result = $stmt->get_result();
				$row = $result->num_rows;
				
				$report = array(
					'status'=> 'ok',
					'product_num' => $row,
					'error'=> $stmt->error
				);
				
			}else{
				$report = array(
					'status'=> 'fail',
					'product_num' => $row,
					'error'=> $stmt->error
				); 
			}
			
		}else{
			$report = array(
				'status'=> 'fail',
				'product_num' => $result->$row,
				'error'=> $stmt->error
			); 
		}
		
		echo json_encode($report);
		*/
		$report = array(
					'status'=> 'fail',
					'product_num' => 0,
					'quantity' => 0,
					'error'=> ''
				);
		
		require_once './admin/includes/connection.php';
		require_once 'includes/API.php';
		
		$myAPI = new API();
		
		
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			// GET REQUEST 
			if(isset($_GET['session_id'])){
				$n = $myAPI->NumOfProduct($_GET['session_id']);
				
				if($n>=0 || n<=10){
					$report['status'] = 'ok';
					$report['product_num'] = $n;
				}else{
					$report['status'] = 'fail';
					$report['error'] = $n;
				}
			}
				
		}else if($_SERVER['REQUEST_METHOD'] == 'POST'){
			// POST REQUEST
			// receive the data
			$data = json_decode(file_get_contents('php://input'), true);
			
			$quantity = $myAPI->SingleProduct($data['user_id'], $data['product_id']);
			
			if($quantity == 0){
				/// insert the product
				$insert = $myAPI->InsertProduct($data['user_id'], $data['product_id'], $data['quantity']);
				
				if($insert == true){
					$report['status'] = 'ok';
					$report['product_num'] = $myAPI->NumOfProduct($data['user_id']);
				}else{
					$report['status'] = 'fail';
					$report['error'] = $insert;
				}
			}else if($quantity == 1){
				/// update the quantity
				$q = $myAPI->SelectProductQuantity($data['user_id'], $data['product_id']);
				$update = $myAPI->UpdateProduct($data['user_id'], $data['product_id'], ($data['quantity']+$q));
				
				if($update == true){
					$report['status'] = 'ok';
					$report['product_num'] = $myAPI->NumOfProduct($data['user_id']);
				}else{
					$report['status'] = 'fail';
					$report['error'] = $update;
				}
			}else{
				$report['status'] = 'fail';
				$report['error'] = $quantity;				
			}
	
		}else if($_SERVER['REQUEST_METHOD'] == 'PUT'){
			// PUT REQUEST
			// receive the data
			$data = json_decode(file_get_contents('php://input'), true);
			
			$update = $myAPI->UpdateProduct($data['user_id'], $data['product_id'], ($data['quantity']));
			
			if($update == true){
				$report['status'] = 'ok';
				$report['quantity'] = $myAPI->SelectProductQuantity($data['user_id'], $data['product_id']);
			}else{
				$report['status'] = 'fail';
				$report['error'] = $update;			
			}			
		}else{
			// DELETE REQUEST
		}
		
		echo json_encode($report);
		
	}		
