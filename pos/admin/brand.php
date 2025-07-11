<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

if (isset($_POST['submit'])) {
  //Prevent Posting Blank Values
 
    $brand_name = $_POST['brandname'];
    $brand_desc = $_POST['description'];
    $brand_code = $_POST['branddcode'];

    $postQuery = "INSERT INTO brand (brand_name, brand_desc, brand_code) VALUES(?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    $rc = $postStmt->bind_param('sss', $brand_name, $brand_desc, $brand_code);
    $postStmt->execute();
    if ($postStmt) {
      $success = "Brand Added";
      header("refresh:1; url=brand.php");
    } else {

      $err = "Please Try Again Or Try Later";
    }
  }


if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $adn = "DELETE FROM  brand  WHERE  brand_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $stmt->close();
  if ($stmt) {
    $success = "Brand Deleted";
    header("refresh:1; url=brand.php");
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
              <a href = " " data-toggle="modal" data-target="#aModal" class="btn btn-outline-success">
              <i class="fas fa-plus-circle"></i>
                 Add New Brand
              </a>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Brand Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>

                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM  brand ";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($prod = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td>
                      <?php echo $prod->brand_code; ?>
                      </td>
                      <td><?php echo $prod->brand_name; ?></td>
                      <td><?php echo $prod->brand_desc; ?></td>
                      <td>
                        <a href="brand.php?delete=<?php echo $prod->brand_id; ?>">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            Delete
                          </button>
                        </a>

                        <a href="update_brand.php?update=<?php echo $prod->brand_id; ?>">
                          <button class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
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

        <!-- Product Modal-->
  <div class="modal fade" id="aModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Brand</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" method="post" >
           <div class="form-group">
             <input class="form-control" placeholder="Brand Name" name="brandname" required>
             <input type= "hidden" name = "branddcode" value="<?php echo $alpha; ?>-<?php echo $beta; ?>" class="form-control">
           </div>
           <div class="form-group">
             <textarea rows="5" cols="50" class="form-control" placeholder="Description" name="description" required></textarea>
           </div>
            <hr>
            <button type="submit" name = "submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i>Save</button>
            <button type="reset" class="btn btn-danger"><i class="fa fa-times fa-fw"></i>Reset</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>      
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