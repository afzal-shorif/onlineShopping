<?php 
	require_once './includes/authenticate.php';			// check user authentication
	require_once './includes/connection.php';				// incluce database connection
	$conn = db_connect("admin");							// create database connection as admin
	
	// default value of input field
	// if not GET request
	$button_text = "Create";						// input type submit value
	$news_title = "";								// title field
    $description = "";
	$visibility = 1; 								// visibility field
	$day = 10;
    $errors = [];
	// insert news 
	if(isset($_POST['submit'])){
	    								// initial empty array for errors
		$title = trim($_POST['title']);				// remove space from both side
		$news_title = trim($_POST['title']);				// remove space from both side
		$num_of_day = (int)$_POST['num_of_day'];	// how many days the news should show
		$visibility = (int)$_POST['visibility'];	// now news is showing or not...
        $description = trim($_POST['description']);
        $file_name = "";
		/// check length of title
		if(strlen($title)<3){
			$errors[] = "Title must be at least 3 characters.";
		}
		if(strlen($title)>60){
			$errors[] = "Title must be less then 60 characters.";
		}
		if($num_of_day < 1){
			$errors[] = "Number of day must be 1 to 365.";
		}

        if(strlen($description)<60){
            $errors[] = "Description must be at least 60 characters.";
        }
        if(strlen($description)>256){
            $errors[] = "Description must be less then 256 characters.";
        }

		if(empty($errors)){
			// no error occure 
			// check the file
			if(!empty($_FILES['image']['name'])){            
                $destination = '../assets/images/news/';		// path for image to store
               
                require_once 'includes/Upload.php';				
                    $max = 102400;								// maximum size of image
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
				// add number of day the news show with current date 
				$to_show = date("Y-m-d", strtotime(" + $num_of_day days"));		// calculate the last date of the news
				
				// insert news if input field submission_type is create
				// update news if input field submission_type is update
				// in update news update image field if image is select
				
				// sql for insert news
				$sql = "INSERT INTO news(title,description, image, to_show, visibility) VALUES (?, ?, ?, ?, ?)";
				
				
				if($_POST['submission_type'] == "update"){
					// update news 
					
					// prevent image_field is image is not select
					if(empty($file_name)) $sql = "UPDATE news SET title = ?, description = ?, to_show = ?, visibility = ? WHERE id=?";
					else $sql = "UPDATE news SET title = ?, description = ?, image = ?, to_show = ?, visibility = ? WHERE id=?";
				}
					
				$stmt = $conn->stmt_init();	
				
				if($stmt = $conn->prepare($sql)){
					
					if($_POST['submission_type'] == "update"){
						// prevent image_field is image is not select
						if(empty($file_name)) $stmt->bind_param('sssii', $title,$description, $to_show, $visibility, $_GET['id']);
						else $stmt->bind_param('ssssii', $title,$description, $file_name, $to_show, $visibility, $_GET['id']);
					}else{
						$stmt->bind_param('ssssi', $title, $description, $file_name, $to_show, $visibility);
					}
					
					if($stmt->execute()){
						$success = "News ".$_POST['submission_type']." Successfully.";	
					}else{
						$errors[] = $stmt->error;
						$errors[] = "Something Wrong Happend.";
					}															
				}else{
					$errors[] = "Something Wrong Happend.";
				}	
			}
		}
	}


	if(isset($_GET['id'])) {

            $button_text = "Update";                    // update the input type submit value
            $id = (int)$_GET['id'];                        // get the id of the news
            $sql = "SELECT *, DATE_FORMAT(to_show,'%Y-%m-%d') as to_show FROM news WHERE id = ?";

            $stmt = $conn->stmt_init();
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows <= 0) header('Location: ../404.php');
                $row = $result->fetch_assoc();            // fetch the result into row variable
                if(empty($errors)){
                    $news_title = $row['title'];
                    $visibility = $row['visibility'];
                    $description = $row['description'];
                }

                $diff = date_diff(date_create(date('Y-m-d')), date_create($row['to_show']));
                $day = $diff->format("%R%a");

            } else {
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
								<input type="text" name="title" class="form-control" id="inputAddress2" placeholder="Title" value="<?= htmlentities($news_title);?>">
							  </div>

                              <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Description</label>
                                    <textarea name="description" class="form-control" id="exampleFormControlTextarea1" rows="2"><?= htmlentities($description);?></textarea>
                              </div>

                              <div class="form-row">
								<div class="form-group col-md-4">
								  <label for="inputCity">No of Day</label>
								  <input type="number" value="<?= $day;?>" name="num_of_day" class="form-control" id="inputCity">
								</div>
								<div class="form-group col-md-4">
								  <label for="inputState">Visibility</label>
								  <select name="visibility" id="inputState" class="form-control">
									<option value="1">Yes</option>
									<option value="0" <?php if($visibility==0) echo "selected";?>>No</option>
								  </select>
								</div>
								<div class="form-group col-md-4">
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