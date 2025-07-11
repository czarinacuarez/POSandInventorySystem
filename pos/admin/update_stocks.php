<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
//Add Customer
if (isset($_POST['updateStocks'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["upd"])) {
    $err = "Blank Values Not Accepted";
  } else {
    $update = $_GET["update"];
    $currentStocks = $mysqli->query("SELECT * FROM stocks;")->fetch_object()->current_quantity;    
    $addedStocks = $_POST["upd"];
    $updatedStocks = $currentStocks + $addedStocks;

    $postQuery = "UPDATE stocks SET current_quantity =?, total_added_qty =? WHERE  stock_id =?";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('sss', $updatedStocks, $addedStocks, $update);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      $success = "Stocks Updated";
      header("refresh:1; url=stocks.php");
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}
require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
  <?php
  require_once('partials/_sidebar.php');
  ?>
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php
    require_once('partials/_topnav.php');

    $update = $_GET['update'];

    $prodID = $mysqli->query("SELECT product_id FROM stocks WHERE stock_id = '$update';")->fetch_object()->product_id;

    $ret = "SELECT *, s.current_quantity - COALESCE((SELECT SUM(qty) FROM transaction_details WHERE product_id = '$prodID'), 0) AS currentstocks
    FROM stocks 
    LEFT JOIN product ON product.product_id = stocks.product_id 
    LEFT JOIN category ON product.category_id = category.category_id 
    LEFT JOIN supplier ON product.supplier_id = supplier.supplier_id
    INNER JOIN (SELECT product_id, current_quantity FROM stocks WHERE stock_id = '$update') AS s ON product.product_id = s.product_id
    WHERE stocks.stock_id = '$update'";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($cust = $res->fetch_object()) {
    ?>
      <!-- Header -->
      <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
        <div class="container-fluid">
          <div class="header-body">
          </div>
        </div>
      </div>
      <!-- Page content -->
      <div class="container-fluid mt--8">
        <!-- Table -->
        <div class="row">
          <div class="col">
            <div class="card shadow">
              <div class="card-header border-0">
                <h3>Please Fill All Fields</h3>
              </div>
              <div class="card-body">
              <form method="POST">
                <div class="form-row">
                  <div class="col">
                    <label>Product Name</label>
                    <input type="text" name="cfirst" value = "<?php echo $cust->product_name; ?>" class="form-control" readonly>
                  </div>
                  <div class="col">
                    <label>Category</label>
                    <input type="text" name="clast" class="form-control" value="<?php echo $cust->product_category; ?>" readonly>
                  </div> 
                  <div class="col">
                    <label>Shop Name</label>
                    <input type="text" name="clast" class="form-control"  value="<?php echo $cust->shop_name; ?>" readonly>
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col">
                    <label>Current Stocks</label>
                    <input type="text" name="curr" class="form-control" value="<?php echo $cust->currentstocks; ?>" readonly>
                  </div> 
                  <div class="col">
                    <label>Stocks Added</label>
                    <input type="text" name="upd" class="form-control" >
                  </div>
                </div>
                <hr>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="updateStocks" value="Update Stocks" class="btn btn-success" value="">
                  </div>
                </div>
              </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Footer -->
      <?php
      require_once('partials/_footer.php');
    }
      ?>
      </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>