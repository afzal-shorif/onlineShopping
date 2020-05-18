<?php
    require_once './includes/authenticate.php';
    require_once './includes/connection.php';		/// include mysqli_connect as $conn object
    $conn = db_connect("admin");				/// open mysqli connection as admin mode.


$sql = "SELECT transaction_id, transaction.user_id,DATE_FORMAT(transaction.date,'%m-%d-%Y')
 as date,first_name,last_name, phone FROM transaction INNER JOIN user_details ON transaction.user_id = user_details.user_id WHERE transaction_id =?";

    if($stmt = $conn->prepare($sql)){
        $id = $_GET['id'];
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row_user = $result->fetch_assoc();


        /// select all product id from transaction_details table
        $sql = "SELECT * FROM transaction_details WHERE transaction_id = $id";
        $transaction_list = $conn->query($sql);
        $total = 0;

    }else{
        echo "Something wrong happend.";
        exit();
    }

    include 'includes/admin_header.php';
    include 'includes/admin_nav.php';

?>
    <div class="col-sm-9 col-md-9">

        <div class="row pt-3">
            <div class="col">
                <strong>Name: </strong><?= $row_user['first_name']." ".$row_user['last_name'];?>
            </div>
            <div class="col">
               <strong>Email: </strong><?= $row_user['user_id'];?>
            </div>
        </div>

        <div class="row pt-3">
            <div class="col">
                <strong>Phone: </strong><?= $row_user['phone'];?>
            </div>
            <div class="col">
                <strong>Date: </strong><?= $row_user['date'];?>
            </div>
        </div>

        <div class="row mt-5">
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
                    foreach($transaction_list as $row){
                        $product_id = $row['product_id'];
                        $result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
                        $product = $result->fetch_assoc();
                        $p = ($product['price']*(float)$row['quantity']);
                        $total += $p;
                    ?>

                        <tr class="">
                            <td>
                                <img src="../assets/images/products/<?= $product['picture'];?>" alt="<?= $product['title'];?>" style="width: 50px;">
                            </td>
                            <td>
                                <a href="../single_product.php?id=<?= $product['product_id'];?>"><?= $product['title'];?></a>
                            </td>

                            <td>
                                <?= $row['quantity'];?>
                            </td>
                            <td>BDT&nbsp;<?= $product['price'];?></td>
                            <td class="productTotal" id="<?= $product['product_id'];?>" ><?= $p;?></td>
                            <td>

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
                            BDT&nbsp;<span id="sum"><?= $total;?></span>
                        </th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
<?php
include 'includes/footer.php';
?>