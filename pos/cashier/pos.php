<?php
include('includes/topp.php');

if (isset($_POST['submit'])) {

  $transaction_code  = $_POST['trancode'];
  $customer  = $_POST['categorys'];
  $cash  = $_POST['cash'];
  $total  = $_POST['total'];
  $staff =  $_SESSION['admin_id'];
  if (!empty($_POST["categorys"]) || !empty($_POST["cash"])){
    if ($total <= $cash){
      
      $itemsQTY = $mysqli->query("SELECT COUNT(cart_id) AS cc FROM cart ")->fetch_object()->cc; 

      $sql = "INSERT INTO transactions (buyer_id,staff_id,transaction_code,no_of_items,cash,total_amt) VALUES (?,?,?,?,?,?)";
      prepared_query($mysqli, $sql, [$customer, $staff, $transaction_code, $itemsQTY , $cash, $total ]);

      $currentTransaction = $mysqli->query("SELECT transaction_id FROM transactions ORDER BY transaction_id DESC LIMIT 1 ")->fetch_object()->transaction_id; 
      
      $change = $cash - $total;
      $ret = "SELECT * FROM   cart  INNER JOIN  product ON product.product_id = cart.product_id 
      INNER JOIN stocks ON stocks.product_id = product.product_id";
      $stmt = $mysqli->prepare($ret);
      $stmt->execute();
      $res = $stmt->get_result();
      while ($prod = $res->fetch_object()) {

        $prodID = $prod->product_id;
        $qty = $prod->qty;
        $price = $prod->price;
        

        $sql = "INSERT INTO transaction_details (transaction_id,product_id,qty,price,transaction_code) VALUES (?,?,?,?,?)";
        prepared_query($mysqli, $sql, [$currentTransaction, $prodID, $qty , $price, $transaction_code]);

      } ?>
      <script>
      Swal.fire(
          'Transacton Success!',
          'Change: <?php echo $change ?>',
          'success'
          )
    </script>
      <?php
      header("refresh:1; url=pos.php");
      $adn = "DELETE FROM  cart";
      $stmt = $mysqli->prepare($adn);
      $stmt->execute();
      $stmt->close();
    } else{ ?>
    <script>
      Swal.fire(
          'Error!',
          'Cash must be greater than total!',
          'error'
          )
    </script>
    
    <?php } ?>

  <?php } else { ?>
        <script>
      Swal.fire(
          'Error!',
          'No Blank Values Allowed!',
          'error'
          )
      </script>
  <?php }
}

if (isset($_POST['addpos'])) {

    $prodID  = $_POST['prodid'];
    $qty  = $_POST['quantity'];
    $price  = $_POST['price'];
    $total  = $qty * $price;

    $currentProduct = $mysqli->query("SELECT product_id FROM cart WHERE product_id = '$prodID'");

    if ($currentProduct->num_rows == 0){
      $currentStocks = $mysqli->query("SELECT s.current_quantity - COALESCE((SELECT SUM(qty) FROM transaction_details WHERE product_id = '$prodID'), 0) AS currentstocks
      FROM stocks s
      INNER JOIN product p ON s.product_id = p.product_id 
      WHERE p.product_id = '$prodID';")->fetch_object()->currentstocks; 
      if ($qty <= $currentStocks){
        $sql = "INSERT INTO cart (product_id,qty,price) VALUES (?,?,?)";
        prepared_query($mysqli, $sql, [$prodID, $qty, $total]);
        header("refresh:1; url=pos.php");
      } else{ ?>
      <script>
        Swal.fire(
            'Limited Stocks!',
            '<?php echo $currentStocks ?> stocks left!',
            'error'
            )
      </script>
      
      <?php } ?>
  
    <?php } else { ?>
          <script>
          Swal.fire(
              'Duplicate Product!',
              'Only choose 1 product and change its quantity!',
              'error'
              )
        </script>
    <?php }
  }

  if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $adn = "DELETE FROM  cart  WHERE  product_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
      header("refresh:1; url=pos.php");
    } else {
    }
  }

  if (isset($_POST['reset'])) {
    $adn = "DELETE FROM  cart";
    $stmt = $mysqli->prepare($adn);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
      header("refresh:1; url=pos.php");
    } else {
    }
  }


?>

                <div class="row">
                <div class="col-lg-12">
                  <div class="card shadow mb-0">
                  <div class="card-header py-2">
                    <h4 class="m-1 text-lg text-primary">Product category</h4>
                  </div>
                        <!-- /.panel-heading -->
                        <div class="card-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                              <li class="nav-item">
                                <a class="nav-link" href="#" data-target="#bags" data-toggle="tab">Bags</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#" data-target="#daily" data-toggle="tab">Daily Essentials</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#drinks" data-toggle="tab">Drinks</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#home" data-toggle="tab">Home Essentials</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#shirts" data-toggle="tab">Shirts & Sweaters</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#toys" data-toggle="tab">Toys</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#valentines" data-toggle="tab">Valentines Day Items</a>
                              </li>
                            </ul>

<?php include 'postabpane.php'; ?>

        <div style="clear:both"></div>  
        <br />  
        <div class="card shadow mb-4 col-md-12">
        <div class="card-header py-3 bg-white">
          <h4 class="m-2 font-weight-bold text-primary">Cart</h4>
        </div>
        
      <div class="row">    
      <div class="card-body col-md-9">
        <div class="table-responsive">

        <!-- trial form lang   --> 
  <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Code</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Brand</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Total</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM   cart  INNER JOIN  product ON product.product_id = cart.product_id 
                  INNER JOIN brand ON brand.brand_id = product.brand_id INNER JOIN category ON category.category_id = product.category_id";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($prod = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td>
                      <?php echo $prod->product_code; ?>
                      </td>
                      <td><?php echo $prod->product_name; ?></td>
                      <td><?php echo $prod->product_name; ?></td>
                      <td><?php echo $prod->brand_name; ?></td>
                      <td><?php echo $prod->qty;?></td>
                      <td>₱<?php echo $prod->product_price; ?></td>
                      <td>₱ <?php echo $prod->price; ?></td>
                      <td>
                        <a href="pos.php?delete=<?php echo $prod->product_id; ?>">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            Delete
                          </button>
                        </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
         </div>
       </div> 


<?php
include 'posside.php';
require_once('partials/_scripts.php');
?>
