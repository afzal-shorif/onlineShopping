<?php
	class API{
		
		protected $conn;
		
		function __construct(){  
			$this->conn = db_connect("read");
		}
		
		
		public function InsertProduct($session_id, $product_id, $quantity){
			$sql = "INSERT INTO tmp (session_id, product_id, quantity) VALUES (?, ?, ?)";
			
			if($stmt = $this->conn->prepare($sql)){
				$stmt->bind_param('sii', $session_id, $product_id, $quantity);
				if($stmt->execute()){
					return true;
				}else{
					return $stmt->error;
				}
			}else{
				return $stmt->error;
			}
		}
		
		public function UpdateProduct($session_id, $product_id, $quantity){
			$sql = "UPDATE tmp SET quantity = ? WHERE session_id = ? AND product_id = ?";
			
			if($stmt = $this->conn->prepare($sql)){
				$stmt->bind_param('isi', $quantity, $session_id, $product_id);
				if($stmt->execute()){
					return true;
				}else{
					return $stmt->error;
				}
			}else{
				return $stmt->error;
			}
		}
		
		
		/// return the quantity of product
		public function SelectProductQuantity($session_id, $product_id){
			$sql = "SELECT * FROM tmp WHERE session_id = ? AND product_id = ?";
			
			if($stmt = $this->conn->prepare($sql)){
				$stmt->bind_param('si', $session_id, $product_id);
				if($stmt->execute()){
					$result = $stmt->get_result();
					$row = $result->fetch_assoc();
					
					return $row['quantity'];
				}else{
					return $stmt->error;
				}
			}else{
				return $stmt->error;
			}
		}
		
		public function Select($session_id){
			$sql = "SELECT * FROM tmp WHERE session_id = ?";
			
			if($stmt = $this->conn->prepare($sql)){
				$stmt->bind_param('s', $session_id);
				if($stmt->execute()){
					$result = $stmt->get_result;
					//$row = $result->fetch_assoc();
					return $result;
				}else{
					return $stmt->error;
				}
			}else{
				return $stmt->error;
			}
		}
		
		///  count the number of product with same session_id and product_id
		public function SingleProduct($session_id , $product_id){
			$sql = "SELECT * FROM tmp WHERE session_id = ? AND product_id = ?";
			
			if($stmt = $this->conn->prepare($sql)){
				$stmt->bind_param('si', $session_id, $product_id);
				
				if($stmt->execute()){
					$result = $stmt->get_result();
					return $result->num_rows;
				}else{
					return $stmt->error;
				}
			}
		}

		///  count the number of product with same session_id
		public function NumOfProduct($session_id){
			$sql = "SELECT * FROM tmp WHERE session_id = ?";
			
			if($stmt = $this->conn->prepare($sql)){
				$stmt->bind_param('s', $session_id);
				
				if($stmt->execute()){
					$result = $stmt->get_result();
					return $result->num_rows;
				}else{
					return $stmt->error;
				}
			}
		}
	}