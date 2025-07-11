<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
//Add Staff
if (isset($_POST['addStaff'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["staff_password"]) ||empty($_POST["staff_email"]) ||empty($_POST["staffcode"]) ||empty($_POST["cfirst"]) || empty($_POST["clast"]) || empty($_POST['cgender']) || empty($_POST['ccontact']) || empty($_POST['cage']) || empty($_POST['cstreet']) || empty($_POST['ccity']) || empty($_POST['cprovince'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $first_name = $_POST['cfirst'];
    $last_name = $_POST['clast'];
    $gender = $_POST['cgender'];
    $contact = $_POST['ccontact'];  
    $age = $_POST['cage'];
    $staff_code = $_POST["staffcode"];
    $staff_email = $_POST["staff_email"];
    $staff_password = $_POST["staff_password"];


    $street = $_POST['cstreet'];
    $city = $_POST['ccity'];
    $province = $_POST['cprovince'];

    $sql = "INSERT INTO street (street_name) VALUES (?)";
    prepared_query($mysqli, $sql, [$street]);

    $sql = "INSERT INTO city (city_name) VALUES (?)";
    prepared_query($mysqli, $sql, [$city]);

    $sql = "INSERT INTO province (province_name) VALUES (?)";
    prepared_query($mysqli, $sql, [$province]);

    $currentID = $mysqli->query("SELECT province_id FROM province ORDER BY province_id DESC LIMIT 1 ")->fetch_object()->province_id; 

    $sql = "INSERT INTO address (street_id,city_id,province_id) VALUES (?,?,?)";
    prepared_query($mysqli, $sql, [$currentID, $currentID, $currentID]);

    $postQuery = "INSERT INTO staff (address_id, contact, sfirst_name, slast_name, gender, age, email, pass, staff_code) VALUES(?,?,?,?,?,?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('sssssssss', $currentID, $contact, $first_name, $last_name, $gender, $age , $staff_email , $staff_password, $staff_code);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      $success = "Staff Added";
      header("refresh:1; url=hrm.php");
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
              <form method="POST">
                <div class="form-row">
                  <div class="col">
                    <label>Staff Code</label>
                    <input type="text" name="staffcode" class="form-control" value="<?php echo $alpha; ?>-<?php echo $beta; ?>">
                  </div>
                  <div class="col">
                    <label>First Name</label>
                    <input type="text" name="cfirst" class="form-control" value="">
                  </div>
                  <div class="col">
                    <label>Last Name</label>
                    <input type="text" name="clast" class="form-control" value="">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col">
                    <label>Gender</label>
                    <select name="cgender" id="gender"  class="form-control">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    </select>
                  </div>
                  <div class="col">
                    <label>Contact</label>
                    <input type="text" name="ccontact" class="form-control" value="">
                  </div>
                  <div class="col">
                    <label>Age</label>
                    <input type="number" name="cage" class="form-control" value="">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                    <div class="col">
                        <label>Street</label>
                        <input type="text" name="cstreet" class="form-control" value="">
                    </div>
                    <div class="col">
                        <label>City</label>
                        <input type="text" name="ccity" class="form-control" value="">
                    </div>
                    <div class="col">
                        <label>Province</label>
                        <input type="text" name="cprovince" class="form-control" value="">
                    </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Staff Email</label>
                    <input type="email" name="staff_email" class="form-control" value="">
                  </div>
                  <div class="col-md-6">
                    <label>Staff Password</label>
                    <input type="password" name="staff_password" class="form-control" value="">
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addStaff" value="Add Staff" class="btn btn-success" value="">
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