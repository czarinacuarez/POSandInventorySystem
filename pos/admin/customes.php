<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $adn = "DELETE buyer, address, street, city, province FROM  buyer INNER JOIN address ON buyer.address_id = address.address_id
  INNER JOIN city ON address.city_id = city.city_id  INNER JOIN street ON
  address.street_id = street.street_id INNER JOIN province ON address.province_id = province.province_id
  WHERE  buyer.buyer_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $stmt->close();
  if ($stmt) {

    $success = "Deleted";
    header("refresh:1; url=customes.php");
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
              <a href="add_customer.php" class="btn btn-outline-success">
                <i class="fas fa-user-plus"></i>
                Add New Customer
              </a>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Full Name</th>
                    <th scope="col">Contact Number</th>
                    <th scope="col">Age</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Location</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM  buyer INNER JOIN address ON buyer.address_id = address.address_id   INNER JOIN city ON address.city_id = city.city_id 
                  INNER JOIN province ON address.province_id = province.province_id INNER JOIN street ON street.street_id = address.street_id";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($cust = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo $cust->first_name;?> <?php echo $cust->last_name;?></td>
                      <td><?php echo $cust->contact; ?></td>
                      <td><?php echo $cust->age; ?></td>
                      <td><?php echo $cust->gender; ?></td>
                      <td><?php echo $cust->street_name; ?>, <?php echo $cust->city_name; ?>, <?php echo $cust->province_name; ?></td>
                      <td class="button-container" style="text-align: left;">
                        <a href="customes.php?delete=<?php echo $cust->buyer_id; ?>">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            Delete
                          </button>
                        </a>
                        <a href="update_customer.php?update=<?php echo $cust->buyer_id; ?>">
                          <button class="btn btn-sm btn-primary">
                            <i class="fas fa-user-edit"></i>
                            Update
                          </button>
                        </a>

                        <a>
                            <form action="PHPPDF.php" method="post">
                              <input type="hidden" name="prints" value="<?php echo $cust->buyer_id;?>">
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