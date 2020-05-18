<?php 
	require_once './includes/authenticate.php';			// check user authentication
	require_once './includes/connection.php';				// incluce database connection
	$conn = db_connect("admin");							// create database connection as admin
	
	// default value of input field
	// if not GET request
	$button_text = "Create";						// input type submit value
	$product_title = "";							// title field
	$visibility = 1; 								// visibility field
	$description = "";
	$category = 1;									
	$price = 0.00;
	
	// insert news 
	if(isset($_POST['submit'])){
		$errors = [];								// initial empty array for errors
		$title = trim($_POST['title']);				// remove space from both side
		$price = (float)$_POST['price'];				// price of the product
		$description = trim($_POST['description']); // discription of the product
		$visibility = (int)$_POST['visibility'];	// now news is showing or not...
		$category = (int)$_POST['category'];
		$file_name = "";
		$product_title = $title;
		/// check length of title
		if(strlen($title)<3){
			$errors[] = "Title must be at least 3 characters.";
		}
		if(strlen($title)>60){
			$errors[] = "Title must be less then 60 characters.";
		}
		
		if(strlen($description)<60){
			$errors[] = "Description must be at least 60 characters.";
		}
		if(strlen($description)>256){
			$errors[] = "Description must be less then 256 characters.";
		}
		
		if($price < 1){
			$errors[] = "Number of day must be 1 to 365.";
		}	
		if(empty($errors)){
			// no error occure 
			// check the file
			if(!empty($_FILES['image']['name'])){            
                $destination = '../assets/images/products/';		// path for image to store
               
                require_once 'includes/Upload.php';				
                    $max = 102400;								// maximum size of image 1 MB
                try {
                    $loader = new Upload($destination);			// create object of upload class
                    $loader->setMaxSize($max);
                    $loader->allowAllTypes(false);				// check the file type is allow or not
                    $loader->upload();							// try to upload
                    $result = $loader->getMessages();			
                    $file_name = $loader->getter();				// get the changed file name
					$errors = array_merge($result);				// marge result array with errors array
				} catch (Exception $e) {
                    $errors[] =  $e->getMessage();				// get the error messages
                }
				
            }
			
			if(empty($errors)){
				
				// insert product if input field (submission_type) is create
				// update product if input field submission_type is update
				// in update product update image field if image is select
				
				// sql for insert product
				$sql = "INSERT INTO products(menu_id, title, description, picture, price, visibility) VALUES (?, ?, ?, ?, ?, ?)";
				
				
				if($_POST['submission_type'] == "update"){
					// update product 
					
					// prevent image_field is image is not select
					if(empty($file_name)){
						$sql = "UPDATE products SET menu_id = ?, title = ?, description = ?,".
								"price = ?, visibility = ? WHERE product_id=?";
					} else{
						$sql = "UPDATE products SET menu_id = ?, title = ?, description = ?,". 
								"picture = ?, price = ?, visibility = ? WHERE product_id=?";
					} 					
				}
					
				//$stmt = $conn->stmt_init();	

				if($stmt = $conn->prepare($sql)){
					
					if($_POST['submission_type'] == "create"){
						$stmt->bind_param('isssdi',$category, $title, $description, $file_name, $price, $visibility);
					}
					
					if($_POST['submission_type'] == "update"){
						// prevent image_field is image is not select
						if(empty($file_name)){
							$stmt->bind_param('issdii',$category, $title, $description, $price, $visibility, $_GET['id']);
						}
						else{
							$stmt->bind_param('isssdii',$category, $title, $description, $file_name, $price, $visibility, $_GET['id']);
						} 							
					}
					
					if($stmt->execute()){
						if($_POST['submission_type'] == "update"){
							$success = "Product Update Successfully.";	
						}else{
							$success = "Product Add Successfully.";	
						}	
					}else{
						$errors[] =  $stmt->error;
						$errors[] = "Something Wrong Happend.";
					}	
				}else{
					$errors[] = "Something Wrong Happend.";
				}	
			}
		}
	}


	if(isset($_GET['id'])){
		$button_text = "Update";					// update the input type submit value
		$id = (int)$_GET['id'];						// get the id of the news
		$sql = "SELECT * FROM products WHERE product_id = ?";
				
		$stmt = $conn->stmt_init();
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i', $id);
			$stmt->execute();									
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();			// fetch the result into row variable
			$product_title = $row['title'];
			$description = $row['description'];
			$price = $row['price'];
			$category = $row['menu_id'];
			$visibility = $row['visibility'];
			
	
		}else{
			$errors[] = "Something Wrong Happend.";
		}
	}
	
	
	include './includes/admin_header.php';
	include './includes/admin_nav.php';
?>

				<div class="col-sm-9 col-md-9">					
					
					
					<div class="row pt-5">
						<div class="col-12 pb-3">							
							<?php 
								if(isset($errors)){
									echo '<ul class="error">';
									foreach($errors as $error){
										echo '<li>'.$error.'</li>';
									}
									echo '</ul>';
								}
								
								if(isset($success)){
									echo '<p class="success">'.$success.'</p>';
								}
								
							?>							
							
							<form class="" method="post" enctype="multipart/form-data">														  
							  <input type="hidden" value="<?= strtolower($button_text);?>" name="submission_type">
							  <div class="form-group">
								<label for="inputAddress2">Title</label>
								<input type="text" name="title" class="form-control" id="inputAddress2" placeholder="Title" value="<?= htmlentities($product_title);?>">
							  </div>							  
								<div class="form-group">
									<label for="exampleFormControlTextarea1">Description</label>
									<textarea name="description" class="form-control" id="exampleFormControlTextarea1" rows="2"><?= htmlentities($description);?></textarea>
								</div>							
							  <div class="form-row">
								<div class="form-group col-md-6">
								  <label for="inputState">Category</label>
								  <select name="category" id="inputState" class="form-control">
										<?php 
											$sql = "SELECT id,name FROM menu";
											$result_menu = $conn->query($sql);
											$selected = "";
											foreach($result_menu as $row){
												if($row['id']==$category) $selected = "selected";
										?>
										<option <?= $selected;?> value="<?= $row['id'];?>"><?= $row['name'];?></option>
										<?php 
											$selected = "";
											}
										?>
								  </select>
								</div>
								
								<div class="form-group col-md-6">
								  <label for="">Price</label>
								  <input type="number" value="<?= htmlentities($price);?>" name="price" class="form-control" id="" step="any">
								</div>
							  </div>
							  
							  <div class="form-row">
								<div class="form-group col-md-6">
								  <label for="inputState">Visibility</label>
								  <select name="visibility" id="inputState" class="form-control">
									<option value="1">Yes</option>
									<option value="0" <?php if($visibility==0) echo "selected";?>>No</option>
								  </select>
								</div>
								<div class="form-group col-md-6">
								    <label for="inputState">Image</label>
									<div class="custom-file">
									  
									  <input type="file" name="image" class="custom-file-input" id="customFile">
									  <label class="custom-file-label" for="customFile">Choose file</label>
									  
									</div>
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