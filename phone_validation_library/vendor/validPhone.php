<?php
    require './autoload.php';
	$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
	$regions = $phoneUtil->getSupportedRegions();
	
	if(isset($_POST['submit'])){
		
	}
				
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <form>
            
            <select>
				<?php
					foreach($regions as $region){
						echo '<option>'.$region.' '.
						$phoneUtil->getCountryCodeForRegion($region).'</option>';
					}	
				?>
            </select>
            <input type="number" name="phone">
        </form>

    </body>
</html>