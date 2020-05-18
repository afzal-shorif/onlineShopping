<?php
    require_once './includes/authenticate.php';
    require_once 'includes/connection.php';		            /// include mysqli_connect as $conn object
	$conn = db_connect("admin");				/// open mysqli connection as admin mode.
	
	$button_text = "Create";
	$disabled = "disabled";
	$menuName = "";
	$position = "";
	// insert menu
	if(isset($_POST['submit'])){
		$errors = [];		// empty array for errors
		$menuName = trim($_POST['name']);
		$menuName = strtolower($menuName);
		// valid menuName
		
		/// check length
		if(strlen($menuName)<3){
			$errors[] = "Menu Name must be at least 3 characters.";
		}
		if(strlen($menuName)>15){
			$errors[] = "Menu Name must be less then 15 characters.";
		}
		
		/// match space
		if (preg_match('/\s/', $menuName)){
			$errors[] = 'Menu name should not contain spaces.';
		}
		
		/// search any digit
		if (preg_match('/\d/', $menuName)){
			$errors[] = 'Menu name should not contain digit.';
		}
		
		/// search any special character
		$pattern = "/[-!$%^&*(){}<>[\]'" . '"|#@:;.,?+=\/\~]/';
        $found = preg_match_all($pattern, $menuName, $matches);
		if ($found){
			$errors[] = 'Menu name should not contain any special character.';
		}
		
		
		if(empty($errors)){
			/// prepare sql statement for execution
			
			/// if create
			if($_POST['submission_type'] == "create"){
				
				/// check name already exist
				$sql = "SELECT * FROM menu WHERE name = ?";
				if($stmt = $conn->prepare($sql)){
					$stmt->bind_param('s',$menuName);
					$stmt->execute();
					$result = $stmt->get_result();								
					
					if($result->num_rows<1){
						/// determine the position for menu
						/// find the max value of postion in menu table
						/// increment the max value for new position
						$sql = "SELECT MAX(position) AS position FROM menu";
						
						$result = $conn->query($sql);
						$row = $result -> fetch_assoc();
						$position = ++$row["position"];
						
						/// insert the menu
						$sql = "INSERT INTO menu(name,position)VALUES('$menuName', $position)";
						if($conn->query($sql)){
							$success = "Menu save successfully.";
						}else {
							$errors[] = "Something Wrong Happend.";
						}	
					}else{
						$errors[] = "$menuName is already exist.";
						
					}	
				}

			}else if($_POST['submission_type'] == "update"){
				/// update menu
				
				try{
					/// get the data using id
					$sql = "SELECT * FROM menu WHERE id = ?";
					if($stmt = $conn->prepare($sql)){
						$id = (int)$_GET['id'];
						
						$stmt->bind_param('i',$id);
						if($stmt->execute()){
							$result = $stmt->get_result();
							$row = $result->fetch_assoc();											
							$position = $row['position'];
							
						}else{
							$errors[] = "Something Wrong Happend.";
							//throw new Exception("Value must be 1 or below");
						}
					}
					
					
					
					
					/// get data form input field position
					$sql = "SELECT * FROM menu WHERE position = ?";

					if($stmt = $conn->prepare($sql) ){
						
						$stmt->bind_param('i',$_POST['position']);					
						
						if($stmt->execute()){
							$result = $stmt->get_result();
							$row = $result->fetch_assoc();
							//print_r($row);
							
							//echo $_POST['position'];
							
							if($row['id'] == $id){
								/// position not change
								$sql = "UPDATE menu SET name = ? WHERE id = ?";			
								
								$stmt = $conn->prepare($sql);
								$stmt->bind_param("si", $menuName, $id);
								if($stmt->execute()){
									$success = "Menu Update Successfully";
								}else {
									$errors[] = "Something Wrong Happend.";
								}
								
							}else{
								/// position change
								/// swap the position
								$sql = "UPDATE menu SET name = ?, position = ? WHERE id = ?";			
								
								$stmt = $conn->prepare($sql);
								$stmt->bind_param("sii",$menuName, $_POST['position'], $id);
								$stmt->execute();
								
								
								$sql = "UPDATE menu SET position = ? WHERE id = ?";
								$stmt = $conn->prepare($sql);
								$stmt->bind_param("ii", $position, $row['id']);
								$stmt->execute();

								$success = "Menu Update Successfully";
							}
							
						}else{
							$errors[] = "Something Wrong Happend.";
						}
						
					}else{
						
						$errors[] = "Something Wrong Happend.";
					}
				
				}catch(Exception $e) {
					$errors = 'Message: ' .$e->getMessage();
				}
	
			}
			
		}
	}
	
	
	if(isset($_GET['id'])){
		$button_text = "Update";
		$disabled = "";
		
		$sql = "SELECT * FROM menu WHERE id = ?";
		
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$_GET['id']);
			if($stmt->execute()){
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
				$menuName = ucwords($row['name']);
				$position = $row['position'];
				
			}else{
				$errors[] = "Something Wrong Happend.";
			}
		}
	}
	
	
	include 'includes/admin_header.php';
	include 'includes/admin_nav.php';
	
	
?>
				<div class="col-sm-9 col-md-9">
					
					<div class="row pt-3">
						<div class="col-12">							
							<?php 
								if(isset($errors)){
									echo '<ul class="error">';
									foreach($errors as $error){
										echo '<li>'.$error.'</li>';
									}
									echo '</ul>';
								}																							
							?>
							<p>Create Menu
								<?php if(isset($success)){
									echo '<span class="success">'.$success.'</span><br>';
								} ?>
								</p>
							
							<form class="" method="post">
								<input type="hidden" name="submission_type" value="<?= strtolower($button_text);?>">
								<div class="form-row">
									<div class="form-group col-md-6">
										<label for="inputState">Name</label>
										<input type="text" name="name" class="form-control" id="inputCity" value="<?=htmlentities($menuName);?>">
									</div>
									<div class="form-group col-md-6">
									  <label for="inputState">Position</label>
									  <select name="position" id="inputState" class="form-control">
										<?php 
											$sql = "SELECT id,position FROM menu";
											$result_menu = $conn->query($sql);
											$selected = "";
											foreach($result_menu as $row){
												if($row['position']==$position) $selected = "selected";
										?>
										<option value="<?= $row['position'];?>" <?= $selected." ";?> <?= $disabled;?> ><?= $row['position'];?></option>
										<?php 
											$selected = "";
											}
										?>
									  </select>
									</div>									
							    </div>
								
								<div style="text-align: right;">
									<button type="submit" name="submit" class="c_btn col-sm-1 col-md-1"><?= $button_text;?></button>
								</div>
							</form>
						</div>
					</div>					
				</div>
<?php
        include 'includes/footer.php';
?>