<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

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
                <b>Product's Current Stocks</b>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Product Code</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Supplier</th>
                    <th scope="col">Current Stocks</th>
                    <th scope="col">Recently Added</th>
                    <th scope="col">Sold</th>
                    <th scope="col">Last Date Updated</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM stocks INNER JOIN product ON 
                  product.product_id = stocks.product_id INNER JOIN supplier ON product.supplier_id = supplier.supplier_id 
                  ORDER BY last_date_updated DESC;";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($prood = $res->fetch_object()) {

                    $prodID = $prood->product_id;

                    $currentStocks = $mysqli->query("SELECT s.current_quantity - COALESCE((SELECT SUM(qty) FROM transaction_details WHERE product_id = '$prodID'), 0) AS currentstocks
                    FROM stocks s
                    INNER JOIN product p ON s.product_id = p.product_id 
                    WHERE p.product_id = '$prodID';")->fetch_object()->currentstocks; 

                    $totalSold = $mysqli->query("SELECT SUM(qty) AS sold FROM transaction_details WHERE product_id = '$prodID'")->fetch_object()->sold; 
                    
                  ?>
                    <tr>
                      <td><?php echo $prood->product_code; ?></td>
                      <td><?php echo $prood->product_name; ?></td>
                      <td><?php echo $prood->shop_name; ?></td>
                      <td><?php echo $currentStocks; ?></td>
                      <td><?php echo $prood->total_added_qty;?></td>
                      <td><?php echo $totalSold ?></td>
                      <td><?php echo $prood->last_date_updated;?></td>
                      <td>
                        <a href="update_stocks.php?update=<?php echo $prood->stock_id; ?>">
                          <button class="btn btn-sm btn-primary">
                            <i class="fas fa-user-edit"></i>
                            Update
                          </button>
                        </a>
                      </td>
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