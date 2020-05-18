<?php
/**
 * file name voucher.php
 * display a form to get user details
 * all input field are in readonly mode expect email field
 * if email is valid javascript send a ajax request to get user details
 * if user is exist in database all field filled up with previous user data
 * if user not exist then active all input field to get user data
 * require PHPMailer library for send mail
 * require libphonenumber library to verify phone number
 * include mailBody.php (html design of mail body) in admin directory
 * the product list in generate in voucher.php
 * and add to the mailBody
 * if all successful, redirect index.php (home page)
 */


	session_start();
	require './phone_validation_library/vendor/autoload.php';
	$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
	$regions = $phoneUtil->getSupportedRegions();
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	

ini_set('display_errors', 1);
error_reporting(E_ALL);

include './config.php';
require_once './admin/includes/connection.php';
$conn = db_connect("read");

$session_id = session_id();
$sql = "SELECT * FROM tmp WHERE session_id = '$session_id'";
$shopping_cart = $conn->query($sql);
$row = $shopping_cart->num_rows;

if($row<=0 ){
    header('Location: 404.php');
    exit();
}


$date = date('m-d-Y');

if(isset($_POST['submit'])){

	$fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $country_code = trim($_POST['country_code']);
    $phone = trim($_POST['phone']);
	$parsePhone = $phoneUtil->parse($phone, $country_code);

    $errors = [];

    if(strlen($fname)<3){
        $errors[] = "First Name must be at least 3 characters.";
    }

    if(strlen($fname)>30){
        $errors[] = "Last Name must be less then 30 characters.";
    }
	
	if(strlen($lname)<3){
        $errors[] = "Name must be at least 3 characters.";
    }

    if(strlen($lname)>30){
        $errors[] = "Name must be less then 30 characters.";
    }
	
	if(strlen($address)>60){
        $errors[] = "Name must be less then 60 characters.";
    }
	
	if(strlen($address)<5){
        $errors[] = "Name must be at least 5 characters.";
    }

    if(strlen($email)<1){
        $errors[] = "Email can't be empty.";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "$email is not valid email address.";
    }

    if(!$phoneUtil->isValidNumber($parsePhone)){
        $errors[] = "Phone number is invalid.";
    }

    /// search any digit
    if (preg_match('/\d/', $fname)){
        $errors[] = 'First Name should not contain digit.';
    }
	
	/// search any digit
    if (preg_match('/\d/', $lname)){
        $errors[] = 'Last Name should not contain digit.';
    }
	
    /// search any special character
    $pattern = "/[-!$%^&*(){}<>[\]'" . '"|#@:;.,?+=\/\~]/';
    $found = preg_match_all($pattern, $fname, $matches);
    if ($found){
        $errors[] = 'Menu name should not contain any special character.';
    }
	
	/// search any special character
    $pattern = "/[-!$%^&*(){}<>[\]'" . '"|#@:;.,?+=\/\~]/';
    $found = preg_match_all($pattern, $lname, $matches);
    if ($found){
        $errors[] = 'Menu name should not contain any special character.';
    }

	
	
    if(empty($errors)){

        //// find max transaction id;
        $transaction_id = 1;

        $sql = "SELECT MAX(transaction_id) as transaction_id FROM transaction";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if($result->num_rows > 0){
            $transaction_id = $row['transaction_id'];
            $transaction_id++;
        }


        /// include mail upper half body...
        /// $htmlContent variable contain mail body
        include_once './admin/mailBody.php';

        /// insert transaction details
        /// collect data from tmp table and insert into transaction table
        $sql = "SELECT * FROM tmp WHERE session_id = '$session_id'";

        $num = 1;
        $sub_total = 0;
        $total = 0;

        if($result = $conn->query($sql)){
            foreach ($result as $result_tmp){

                $session_id = $result_tmp['session_id'];
                $product_id = $result_tmp['product_id'];
                $quantity = $result_tmp['quantity'];

				/// insert transaction details
                $sql = "INSERT INTO transaction_details (transaction_id, product_id, quantity) VALUES ($transaction_id, $product_id, $quantity)";
                $result_insert = $conn->query($sql);

                /// build mail body with product_id

                $sql_product = "SELECT * FROM products WHERE product_id = $product_id";
                $result_product = $conn->query($sql_product);
                $row_product = $result_product->fetch_assoc();

                $sub_total = $row_product['price']*$quantity;
                $htmlContent .= '<tr class=""> <td>'.$num++.'</td><td>'
                    .$row_product['title'].
                    '</td><td>'
                    .$quantity.
                    '</td><td>BDT&nbsp; '
                    .$row_product['price'].
                    '</td><td>'
                    .$sub_total.
                    '</td></tr>';
                $total += $sub_total;
                $sub_total = 0;
            }

            /// complete mail body
            $htmlContent .= $htmlContent_total;
            $htmlContent .= '<th colspan="3">BDT&nbsp;'.$total.'</th>';
            $htmlContent .= $htmlContent_footer;
			
			/// check user already exist or not
			$sql_find_user = "SELECT * FROM user_details WHERE user_id = '$email'";
			$user_result = $conn->query($sql_find_user);


			if($user_result->num_rows <= 0){
				/// insert the user_details
				
				$sql = "INSERT INTO user_details (user_id,first_name, last_name, address, region, phone)
						VALUES (?, ?, ?, ?, ?, ?)";
						
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ssssss',$email, $fname, $lname, $address, $country_code, $phone);
				
				if(!$stmt->execute()){
					die($stmt->error);
				}
			}
			
			$sql = "INSERT INTO transaction(user_id)VALUES('$email')";
			$conn->query($sql);
 			

			/// delete from tmp table
			$sql_delete_tmp = "DELETE FROM tmp WHERE session_id = '$session_id'";
			$result_delete = $conn->query($sql_delete_tmp);

			//// send mail
			/// mail body included
			/// $htmlContent variable contain mail body


			$to = $email;
			$subject = "Transaction Details";
			$message = "Dear: $fname"." $lname\r\n\r\n";
			$message .= "Thankyou for using our Service\n\r\n\r";

			$message .= $htmlContent;

			require_once 'PHPMailer/src/PHPMailer.php';
			require_once 'PHPMailer/src/SMTP.php';
			require_once 'PHPMailer/src/Exception.php';


			$mail = new PHPMailer(true);

			try {
				//Server settings
				//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
				$mail->isSMTP();                                            // Send using SMTP
				$mail->Host       = 'mail.easysqlbd.com';            // Set the SMTP server to send through
				//$mail->Host       = 'smtp.gmail.com';            // Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
				$mail->Username   = 'hr.admin@easysqlbd.com';                     // SMTP username
				$mail->Password   = 'admin123test';                               // SMTP password
				//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
				//$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

				//Recipients
				$mail->setFrom('hr.admin@easysqlbd.com');
				$mail->addAddress($email);     // Add a recipient
				//$mail->addAddress('ellen@example.com');               // Name is optional
				//                       // $mail->addReplyTo('info@example.com', 'Information');


				// Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = $subject;
				$mail->Body    = $message;
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				$mail->send();
				header('Location: index.php');
			} catch (Exception $e) {
				die("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
			}
          
        }
    }
}

$title = strtoupper($site_title)." :: Voucher";

$today = date('m-d-Y');

include './includes/header.php';
?>

    <div class="container">
    <div class="row mt-5 mb-5">
        <div class="col">
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
            <form method="post" name="user_details">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="inputEmail4">Email</label>
                        <input oninput="EmailValidation(this)" type="email" class="form-control" id="inputEmail4" placeholder="Email" name="email">
                    </div>                    
                </div>												
				<div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="validationDefault01">First Name</label>
                        <input readonly type="text" class="form-control" id="validationDefault01" placeholder="First Name" name="fname" value="">
                    </div>                
                    <div class="form-group col-md-6">
                        <label for="validationDefault02">Last Name</label>
                        <input readonly type="text" class="form-control"  placeholder="Last Name" name="lname">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputAddress">Address</label>
						<input readonly type="text" class="form-control" id="inputAddress" placeholder="1234 Main St" name="address">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Country </label>
                        <select class="custom-select" name="country_code">
							<?php
								foreach($regions as $region){
									echo '<option value="'.$region.'">'.$region.' '.
									$phoneUtil->getCountryCodeForRegion($region).'</option>';
								}	
							?>
						</select>
                    </div>
					<div class="form-group col-md-4">
                        <label for="">Phone </label>
                        <input readonly type="text" class="form-control" id="inputPassword4" placeholder="Phone" name="phone">
                    </div>					
                </div>
                <div class="col text-right">
                    <button disabled type="submit" name="submit" class="_btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
	<script>
		var email = document.forms['user_details']['email'];
		var first_name = document.forms['user_details']['fname'];
		var last_name = document.forms['user_details']['lname'];
		var address = document.forms['user_details']['address'];
		var country_code = document.forms['user_details']['country_code'];
		var phone = document.forms['user_details']['phone'];
		var button = document.forms['user_details']['submit'];
		
		function EmailValidation(event){
			
			first_name.setAttribute('readonly' ,'readonly');
			last_name.setAttribute('readonly','readonly');
			address.setAttribute('readonly','readonly');
			//country_code.setAttribute('readonly','readonly');
			phone.setAttribute('readonly','readonly');
			button.setAttribute('disabled','disabled');
			
			first_name.value = '';
			last_name.value = '';
			address.value = '';
			country_code.value = 'US';
			phone.value = '';
			

			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			
			if(!re.test(String(email.value).toLowerCase())) return;
			/// mail address is valid
			/// check user
			
			/// prepare post data
			var data  = {
				user_id: email.value
			};
			
			
			var xhr = new XMLHttpRequest(); 					/// http request object

			/// request end point
			xhr.open("POST", "getUserData.php", true);			/// open the request
			
			//xhr.setRequestHeader('Content-Type', 'application/json');
			/// request header
			xhr.setRequestHeader('X-Requested-With','nothing');
			
			xhr.onreadystatechange = function (){
			
				if(xhr.readyState == 2){
					/// on loading...
				}
				
				if(xhr.readyState == 4 && xhr.status == 200){

					var result = JSON.parse(xhr.responseText); 			/// parse the json data into javascrit array
					//var result = xhr.responseText;
					
						
					if(result['status'] == 'found'){
						/// request successful
						//first_name.setAttribute('value',result['first_name']);
						
						first_name.value = " "+result['first_name']+"";	
						last_name.value = result['last_name'];
						address.value = result['address'];
						country_code.value = result['region'];
						phone.value = result['phone'];
						
					}else{
						/// request not successful
						
						first_name.removeAttribute('readonly');
						last_name.removeAttribute('readonly');
						address.removeAttribute('readonly');						
						phone.removeAttribute('readonly'); 
					}
					
					button.removeAttribute('disabled');
					//console.table(result);
				}
			}

			xhr.send(JSON.stringify(data));
		}
		
	
	</script>
<?php
include 'includes/footer.php';
?>