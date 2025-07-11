<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
if (isset($_POST['updateProduct'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["categorys"]) ||empty($_POST["brand"]) ||empty($_POST["supplier"]) || empty($_POST["prod_name"]) || empty($_POST['prod_desc']) || empty($_POST['prod_price'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $prod_name = $_POST['prod_name'];
    $prod_desc = $_POST['prod_desc'];
    $prod_price = $_POST['prod_price'];
    $category = $_POST['categorys'];
    $brand = $_POST['brand'];
    $supplier = $_POST['supplier'];
    $update = $_GET['update'];

    //Insert Captured information to a database table
    $postQuery = "UPDATE product SET supplier_id =?, category_id =?, brand_id =?, product_name =?, product_description =? , product_price =? WHERE product_id = ?";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('sssssss', $supplier, $category, $brand, $prod_name, $prod_desc, $prod_price, $update);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      $success = "Product Updated";
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
    $update = $_GET['update'];
    $ret = "SELECT * FROM  product LEFT JOIN brand ON product.brand_id = brand.brand_id LEFT JOIN supplier ON 
    product.supplier_id = supplier.supplier_id LEFT JOIN category ON product.category_id = category.category_id
    WHERE product.product_id = '$update' ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($prod = $res->fetch_object()) {
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
                    <input type="text" name="prod_name" value ="<?php echo $prod->product_name ?>" class="form-control">
                  </div>
                  <div class="col">
                    <label>Product Price</label>
                    <input type="number" name="prod_price" value ="<?php echo $prod->product_price ?>"class="form-control">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col">
                    <label>Product Category</label>
                    <?php
                      $ret = "SELECT * FROM category ";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      ?>
                    <select name="categorys" class="form-control" required>
                    <option value ="<?php echo $prod->category_id ?>" selected><?php echo $prod->product_category ?></option>
                    <?php while ($ri = $res->fetch_array()) {  ?>
                    <option value=<?php echo $ri['category_id'] ?>><?php echo $ri['product_category'] ?></option>
                    <?php }?>
                    </select>
                  </div>
                  <div class="col">
                    <label>Product Brand</label>
                    
                    <select name="brand" class="form-control" required>
                    <?php if (is_null($prod->brand_id)){ ?>
                      <option value ="" selected></option>
                    <?php } else { ?>
                      <option value ="<?php echo $prod->brand_id ?>" selected><?php echo $prod->brand_name ?></option>
                    <?php } ?>
                      <?php
                      $ret = "SELECT * FROM brand ";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      ?>
                    <?php while ($ri = $res->fetch_array()) {  ?>
                    <option value=<?php echo $ri['brand_id'] ?>><?php echo $ri['brand_name'] ?></option>
                    <?php }?>
                    </select>
                  </div>
                  <div class="col">

                    <label>Product Supplier</label>
                    <select name="supplier" class="form-control" required>
                    <?php if (is_null($prod->supplier_id)){ ?>
                      <option value ="" selected></option>
                    <?php } else { ?>
                      <option value ="<?php echo $prod->supplier_id ?>" selected><?php echo $prod->shop_name ?></option>
                    <?php } ?>

                    <?php
                      $ret = "SELECT * FROM supplier ";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                      ?>
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
                    <textarea rows="5" name="prod_desc" class="form-control" ><?php echo $prod->product_description ?></textarea>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="updateProduct" value="Update Product" class="btn btn-success" value="">
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