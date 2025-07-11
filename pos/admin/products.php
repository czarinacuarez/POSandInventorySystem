<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $adn = "DELETE FROM  product  WHERE  product_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $stmt->close();
  if ($stmt) {
    $adn = "DELETE FROM transactions
    WHERE transaction_code NOT IN (SELECT transaction_code FROM transaction_details);";
    $stmt = $mysqli->prepare($adn);
    $stmt->execute();
    $stmt->close();

    $ret = "SELECT * FROM transactions";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($prood = $res->fetch_object()) {

                    $code = $prood->transaction_code;
                    $ret = "UPDATE transactions
                    SET no_of_items = (
                        SELECT COUNT(*) 
                        FROM transaction_details 
                        WHERE transaction_code = '$code'
                    )
                    WHERE transaction_code = '$code';";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute();

                  }
    $success = "Deleted"; 
    header("refresh:1; url=products.php");
  } else {
    $err = "Try Again Later";
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
              <a href="add_product.php" class="btn btn-outline-success">
              <i class="fas fa-plus-circle"></i>
                 Add New Product
              </a>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Product Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Brand</th>
                    <th scope="col">Price</th>
                    <th scope="col">Supplier</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM   product  LEFT JOIN  category ON product.category_id = category.category_id 
                  LEFT JOIN brand ON brand.brand_id = product.brand_id LEFT JOIN supplier ON product.supplier_id = supplier.supplier_id" ;
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
                      <td><?php echo $prod->product_category; ?></td>
                      <td><?php echo $prod->brand_name;?></td>
                      <td>₱ <?php echo $prod->product_price; ?></td>
                      <td><?php echo $prod->shop_name;?></td>
                      <td class="button-container" style="text-align: left;">
                          <a href="products.php?delete=<?php echo $prod->product_id; ?>">
                            <button class="btn btn-sm btn-danger">
                              <i class="fas fa-trash"></i>
                              Delete
                            </button>
                          </a>

                          <a href="update_product.php?update=<?php echo $prod->product_id; ?>">
                            <button class="btn btn-sm btn-primary">
                              <i class="fas fa-edit"></i>
                              Update
                            </button>
                          </a>

                          <a>
                            <form action="PHPPDF.php" method="post">
                              <input type="hidden" name="print" value="<?php echo $prod->product_id;?>">
                              <button type="submit" class="btn btn-sm btn-info">
                                <i class="fas fa-print"></i>
                              </button>
                            </form>
                          </a>
                        </td>

                        <style>
                          .button-container > * {
                            display: inline-flex ;
                          }
                        </style>

                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php
      require_once('partials/_footer.php');
      ?>
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>