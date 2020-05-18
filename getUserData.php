<?php 
	/**
     * file name getUserData.php
     * use for ajax request
     * get a POST request with user_id(user email) in json format
     * send user details for that user_id (user email)
     * if not found the user_id (user_email) in user_details table send empty array (json format)
	*/
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'nothing'){
		
		$report = array(
			'status'=> 'not found',
			'first_name' => 0,
			'last_name' => 0,
			'address'=> '',
			'region'=> '',
			'phone'=> '',
		);
		
		require_once './admin/includes/connection.php';
		$conn = db_connect("read");
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			// POST REQUEST
			// receive the data
			$data = json_decode(file_get_contents('php://input'), true);
			
			$sql = "SELECT * FROM user_details WHERE user_id = '".$data['user_id']."'";
			$result = $conn->query($sql);
			
			
			if($result->num_rows >= 1){
				$result = $result->fetch_assoc();
				$report['status'] = 'found';
				$report['first_name'] = $result['first_name'];
				$report['last_name'] = $result['last_name'];
				$report['address'] = $result['address'];
				$report['region'] = $result['region'];
				$str = $result['phone'];
				$report['phone'] = $str;
				//$report['phone'] = 'XXX...'.$str[strlen($str)-2].$str[strlen($str)-1];
			}
		}
		
		echo json_encode($report);
		
	}		
