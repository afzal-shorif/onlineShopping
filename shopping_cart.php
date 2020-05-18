<?php
/**
 * file name shopping_cart.php
 * display the cart list with increment, decrement and delete button
 * increment and decrement button use ajax request in saveJsonData.php (JSON format) to update the user cart list
 * delete button use product id in remove.php file to delete the product from cart list
 * to select the increment and decrement button, use javascript childNodes
 * use custom html attribute "data-product-id" in increment and decrement button to get product id
 */
	session_start();
	require_once './admin/includes/connection.php';
	$conn = db_connect("read");
    include './config.php';
    $title = strtoupper($site_title)." :: Checkout";


	$session_id = session_id();
	$sql = "SELECT * FROM tmp WHERE session_id = '$session_id'";
	$shopping_cart = $conn->query($sql);
	$total = 0;
	
	include './includes/header.php';
?>
		<div class="container">
			<div class="row">
				<div class="col">
					<table class="table">
						<thead>
							<tr>
								<th colspan="2">Product</th>
								<th style="min-width: 150px;">Quantity</th>
								<th style="min-width: 90px;">Unit price</th>
								<th colspan="2">Total</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($shopping_cart as $row){
									$product_id = $row['product_id'];
									$result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
									if($result->num_rows <= 0) header('Location: 404.php');             /// if product not found
									$product = $result->fetch_assoc();
									$p = ($product['price']*(float)$row['quantity']);                           /// product sub total
									$total += $p;                                                               /// total price
							?>
                                    <script>
                                        single_product_price[<?= $product_id;?>]=<?=$product['price'];?>;
                                    </script>
							<tr class="">
								<td>
									<a href="#">
									   <img src="assets/images/products/<?= $product['picture'];?>" alt="" style="width: 50px;">
									</a>
								</td>              
								<td>
									<a href="single_product.php?id=<?= $product['product_id'];?>"><?= $product['title'];?></a>
								</td>
								<!-- Modal -->                                                    
								<td>
									<div class="">
										<button type="button" data-product-id="<?= $product['product_id'];?>" class="form-control sub" style="width: 35px; display: inline-block;">-</button>
										<input name="" type="input" min="1" max="10" value="<?= $row['quantity'];?>" class="form-control text-center" readonly="" style="max-width: 35px; display: inline-block; padding: 6px 5px;">
										<button type="button" data-product-id="<?= $product['product_id'];?>" class="form-control add" style="width: 35px; display: inline-block;" >+</button>
									</div>
								</td>
								<td>BDT&nbsp;<?= money_format('%i', $product['price']);?></td>
								<td class="productTotal" id="<?= $product['product_id'];?>" > <?= money_format('%i', $p); ?></td>
								<td>
									<a href="remove.php?product_id=<?= $product['product_id'];?>">
										<i class="fa fa-trash-o"></i>
									</a>
								</td>				
							</tr>
							<?php 
								}
							?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="3"></th>
								<th class="text-right">Total</th>
								<th colspan="3">
									BDT&nbsp;<span id="sum"><?= money_format('%i', $total);?></span>
								</th>
							</tr>
						</tfoot>
                    </table>
				</div>
			</div>
			<div class="row" id="checkoutProceed">
				<div class="col text-right">
					<a href="index.php"><button class="_btn">Continue Shopping</button></a>
                    <a href="voucher.php"><button class="_btn">Proceed to checkout</button></a>
				</div>
			</div>

	<?php 
		include 'includes/footer.php';
	?>