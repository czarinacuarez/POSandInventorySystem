<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
//Add Customer
if (isset($_POST['updateSupplier'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["shopp"]) ||empty($_POST["cfirst"]) || empty($_POST["clast"]) || empty($_POST['cgender']) || empty($_POST['ccontact']) || empty($_POST['cage']) || empty($_POST['cstreet']) || empty($_POST['ccity']) || empty($_POST['cprovince'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $first_name = $_POST['cfirst'];
    $last_name = $_POST['clast'];
    $gender = $_POST['cgender'];
    $contact = $_POST['ccontact'];  
    $age = $_POST['cage'];

    $street = $_POST['cstreet'];
    $city = $_POST['ccity'];
    $province = $_POST['cprovince'];
    $shop = $_POST['shopp'];
    $update = $_GET['update'];



    $postQuery = "UPDATE supplier INNER JOIN address ON supplier.address_id = address.address_id  INNER JOIN city ON address.city_id = city.city_id 
    INNER JOIN street ON address.street_id = street.street_id INNER JOIN province ON address.province_id = province.province_id
    SET supplier.first_name =?, supplier.last_name =?, supplier.gender =?, supplier.contact =? , supplier.age = ? , 
    street.street_name = ? , city.city_name = ? , province.province_name = ? , supplier.shop_name = ? WHERE  supplier.supplier_id =?";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('ssssssssss', $first_name, $last_name, $gender, $contact, $age, $street, $city, $province, $shop, $update);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      $success = "Supplier Updated";
      header("refresh:1; url=suppliers.php");
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

    $ret = "SELECT * FROM  supplier INNER JOIN address ON supplier.address_id = address.address_id 
    INNER JOIN city ON city.city_id = address.city_id INNER JOIN street ON street.street_id = address.street_id
    INNER JOIN province ON province.province_id = address.province_id WHERE supplier_id = '$update' ";
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
                    <label>First Name</label>
                    <input type="text" name="cfirst" value = "<?php echo $cust->first_name; ?>" class="form-control">
                  </div>
                  <div class="col">
                    <label>Last Name</label>
                    <input type="text" name="clast" class="form-control" value="<?php echo $cust->last_name; ?>">
                  </div> 
                  <div class="col">
                    <label>Shop Name</label>
                    <input type="text" name="shopp" class="form-control" value="<?php echo $cust->shop_name; ?>">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col">
                    <label>Gender</label>
                    <select name="cgender" id="gender"  class="form-control">
                    <option value="<?php echo $cust->gender; ?>" selected><?php echo $cust->gender; ?></option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    </select>
                  </div>
                  <div class="col">
                    <label>Contact</label>
                    <input type="text" name="ccontact" class="form-control" value="<?php echo $cust->contact; ?>">
                  </div>
                  <div class="col">
                    <label>Age</label>
                    <input type="number" name="cage" class="form-control" value="<?php echo $cust->age; ?>">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                    <div class="col">
                        <label>Street</label>
                        <input type="text" name="cstreet" class="form-control" value="<?php echo $cust->street_name; ?>">
                    </div>
                    <div class="col">
                        <label>City</label>
                        <input type="text" name="ccity" class="form-control" value="<?php echo $cust->city_name; ?>">
                    </div>
                    <div class="col">
                        <label>Province</label>
                        <input type="text" name="cprovince" class="form-control" value="<?php echo $cust->province_name; ?>">
                    </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="updateSupplier" value="Update Supplier" class="btn btn-success" value="">
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