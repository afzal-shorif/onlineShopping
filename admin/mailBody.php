<?php
    //require_once './includes/authenticate.php';
$htmlContent = '<!DOCTYPE html>
<html>
<head>
	<style rel="stylesheet">
		body{
			font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
			font-size: 1rem;
			font-weight: 400;
			line-height: 1.5;
			color: #212529;
			text-align: left;
		}
		
		.table{
			width: 100%;
			max-width: 100%;
			margin-bottom: 1rem;
			background-color: transparent;
		}
		th{
			vertical-align: bottom;
			padding: .75rem;
			border-top: 1px solid #dee2e6;
			text-align: left;
		}
		td{
			padding: .75rem;
			vertical-align: top;
			border-top: 1px solid #dee2e6;
		}
	</style>
</head>
<body style="margin-top: 10px;">
	<div style="padding: 20px;">
	
	<div style="text-align: center; margin-bottom: 40px;">
		<h4 style="margin: 0px; font-size: 30px; background-color: #626a6a;">'.strtoupper($site_title).'</h4>
		<h5 style="margin: 0px;">make your choice</h5>
		<p style="margin: 0px;">info@'.$site_title.'.com, admin@'.$site_title.'.com'.'</P>
		<p style="margin: 0px;">017XXXXXXXX, 018XXXXXXXX, 019XXXXXXXX</P>
	</div>
	<div style="text-align: center;">
		<strong style="border: 2px solid; border-radius: 5px; background-color: #ccc; padding: 3px 10px;">Invoice</strong>
	</div>
	<div>';
/// serial number
$htmlContent .= '<p><strong>Serial No: '.$transaction_id.'</strong></p>';
$htmlContent .= '</div>
	<div style="width: 100%; display: inline-block; overflow: hidder;">';
/// user name
$htmlContent .= '<div style="width: 50%; float: left;"><strong>Name: </strong>'.$fname.' '.$lname.'</div>';
$htmlContent .= '<div style="width: 50%; float: left;"><strong>Email: </strong>'.$email.'</div>';
$htmlContent .= '</div>
	<div style="width: 100%; display: inline-block; overflow: hidder;">';
$htmlContent .= '<div style="width: 50%; float: left;"><strong>Phone: </strong>'.$phone.'</div>';
$htmlContent .= '<div style="width: 50%; float: left;"><strong>Date: </strong>'.$date.'</div>';
$htmlContent .= '</div>
	
	<div style="width: 100%; margin: 0 auto; margin-top: 20px; margin-bottom: 50px;">
		<table class="table">
			<thead>
			<tr>
				<th>#</th>
				<th>Product</th>
				<th style="max-width: 90px;">Quantity</th>
				<th style="max-width: 90px;">Unit price</th>
				<th style="max-width: 90px;">Total</th>
			</tr>
			</thead>
			<tbody>';

$htmlContent_total = '</tbody>
			<tfoot>
			<tr>
				<th colspan="3"></th>
				<th class="text-right">Total</th>';



$htmlContent_footer = '</tr>
			</tfoot>
		</table>
	</div>
	</div>
</body>
</html>';

?>