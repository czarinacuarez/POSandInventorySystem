<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
if (isset($_POST['addProduct'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["qq"]) ||empty($_POST["categorys"]) ||empty($_POST["brand"]) ||empty($_POST["supplier"]) ||empty($_POST["prod_code"]) || empty($_POST["prod_name"]) || empty($_POST['prod_desc']) || empty($_POST['prod_price'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $prod_code  = $_POST['prod_code'];
    $prod_name = $_POST['prod_name'];
    $prod_desc = $_POST['prod_desc'];
    $prod_price = $_POST['prod_price'];
    $category = $_POST['categorys'];
    $brand = $_POST['brand'];
    $supplier = $_POST['supplier'];
    $quantity = $_POST['qq'];


    $default = 0;

    $sql = "INSERT INTO product (supplier_id,category_id,brand_id,product_name,product_description,product_price, product_code) VALUES (?,?,?,?,?,?,?)";
    prepared_query($mysqli, $sql, [$supplier, $category, $brand , $prod_name, $prod_desc, $prod_price, $prod_code]);

    $currentID = $mysqli->query("SELECT product_id FROM product ORDER BY product_id DESC LIMIT 1 ")->fetch_object()->product_id; 

    $postQuery = "INSERT INTO stocks (product_id, current_quantity, total_sold_qty, total_added_qty) VALUES(?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('ssss', $currentID, $quantity, $default, $default);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      $success = "Product Added";
      header("refresh:1; url=products.php");
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
              <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="col">
                    <label>Product Name</label>
                    <input type="text" name="prod_name" class="form-control">
                  </div>
                  <div class="col">
                    <label>Product Price</label>
                    <input type="number" name="prod_price" class="form-control">
                  </div>
                  
                  <div class="col">
                    <label>Product Code</label>
                    <input type="text" name="prod_code" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" class="form-control" value="">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                <div class="col">
                    <label>Product Quantity</label>
                    <input type="number" name="qq" class="form-control">
                  </div>
                  <div class="col">
                    <label>Product Category</label>
                    <?php
                      $ret = "SELECT * FROM category ";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      ?>
                    <select name="categorys" class="form-control" required>
                    <?php while ($ri = $res->fetch_array()) {  ?>
                    <option value=<?php echo $ri['category_id'] ?>><?php echo $ri['product_category'] ?></option>
                    <?php }?>
                    </select>
                  </div>
                  <div class="col">
                    <label>Product Brand</label>
                    <?php
                      $ret = "SELECT * FROM brand ";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      ?>
                    <select name="brand" class="form-control" required>
                    <?php while ($ri = $res->fetch_array()) {  ?>
                    <option value=<?php echo $ri['brand_id'] ?>><?php echo $ri['brand_name'] ?></option>
                    <?php }?>
                    </select>
                  </div>
                  <div class="col">
                  <?php
                      $ret = "SELECT * FROM supplier ";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      ?>
                    <label>Product Supplier</label>
                    <select name="supplier" class="form-control" required>
                    <?php while ($ri = $res->fetch_array()) {  ?>
                    <option value=<?php echo $ri['supplier_id'] ?>><?php echo $ri['shop_name'] ?></option>
                    <?php }?>
                    </select>
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-12">
                    <label>Product Description</label>
                    <textarea rows="5" name="prod_desc" class="form-control" value=""></textarea>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addProduct" value="Add Product" class="btn btn-success" value="">
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
      ?>
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>