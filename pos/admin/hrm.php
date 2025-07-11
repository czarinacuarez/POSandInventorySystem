<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
//Delete Staff
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $adn = "DELETE staff, address, street, city, province FROM  staff INNER JOIN address ON staff.address_id = address.address_id
  INNER JOIN city ON address.city_id = city.city_id  INNER JOIN street ON
  address.street_id = street.street_id INNER JOIN province ON address.province_id = province.province_id
  WHERE  staff.staff_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();
  if ($stmt) {
    $success = "Deleted";
    header("refresh:1; url=hrm.php");
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
              <a href="add_staff.php" class="btn btn-outline-success"><i class="fas fa-user-plus"></i> Add New Staff</a>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Staff Code</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Age</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Location</th>
                    <th scope="col">Email</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM  staff INNER JOIN address ON staff.address_id = address.address_id  INNER JOIN city ON address.city_id = city.city_id 
                  INNER JOIN province ON address.province_id = province.province_id INNER JOIN street ON street.street_id = address.street_id";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($staff = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo $staff->staff_code; ?></td>
                      <td><?php echo $staff->sfirst_name; ?> <?php echo $staff->slast_name; ?></td>
                      <td><?php echo $staff->contact; ?></td>
                      <td><?php echo $staff->age; ?></td>
                      <td><?php echo $staff->gender; ?></td>
                      <td><?php echo $staff->street_name; ?>, <?php echo $staff->city_name; ?>, <?php echo $staff->province_name; ?></td>
                      <td><?php echo $staff->email; ?></td>
                      <td>
                        <a href="hrm.php?delete=<?php echo $staff->staff_id; ?>">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            Delete
                          </button>
                        </a>

                        <a href="update_staff.php?update=<?php echo $staff->staff_id; ?>">
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