<?php session_start();
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
        <?php require_once('partials/_topnav.php'); ?>
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
                    <div class="container">
                        <div class="row">
                        <div class="col-md-3">
                        <form action = "PHPExcel.php" method = "post">
                            <button name = "excel"  class="btn btn-outline-success">Export Report to Excel</button>
                        </form>         
                        </div>
                        <div class="col-md-3">
                        <a href=""  data-toggle="modal" data-target="#PDFModal" class="btn btn-outline-success">
                            <i class="fas fa-user-plus"></i>
                                Export Report to PDF
                        </a>
                        </div>
                        <div class="col-md-3">
                        <a href=""  data-toggle="modal" data-target="#DatabaseModal" class="btn btn-outline-success">
                            <i class="fas fa-user-plus"></i>
                                Export Report to Database
                        </a>                     
                        </div>
                        </div>
                    </div>
                    </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-success" scope="col">Code</th>
                                        <th scope="col">Customer</th>
                                        <th class="text-success" scope="col"># of items</th>
                                        <th class="text-success" scope="col">Total Amount</th>
                                        <th scope="col">Staff</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM  transactions LEFT JOIN buyer ON buyer.buyer_id = transactions.buyer_id
                                    LEFT JOIN staff ON staff.staff_id = transactions.staff_id  ORDER BY `date` DESC  ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {

                                    ?>
                                        <tr>
                                            <th class="text-success" scope="row"><?php echo $order->transaction_code; ?></th>
                                            <td><?php echo $order->first_name;?> <?php echo $order->last_name;?></td>
                                            <td class="text-success"><?php echo $order->no_of_items; ?></td>
                                            <td class="text-success" >₱<?php echo $order->total_amt; ?></td>
                                            <td><?php echo $order->sfirst_name; ?> <?php echo $order->slast_name; ?></td>
                                            <td><?php echo date('d/M/Y g:i', strtotime($order->date)); ?></td>
                                            <td>
                                            <button class="btn btn-sm btn-info view-transaction" data-toggle="modal" data-target="#viewModal" data-transaction_code="<?php echo $order->transaction_code; ?>">
                                            <i class="fas fa-info-circle"></i>
                                            </button>
                                            </td>  
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (isset($_GET['success'])) { ?>
				        <script>
                            Swal.fire(
                            'Success',
                            '<?php echo $_GET['success']; ?>',
                            'success'
                            )
				        </script>
                    <?php } ?>

                    <?php if (isset($_GET['error'])) { ?>
				        <script>
                            Swal.fire(
                            'Error',
                            '<?php echo $_GET['error']; ?>',
                            'error'
                            )
				        </script>
                    <?php } ?>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <?php require_once('partials/_footer.php'); ?>
        </div>
    </div>
    <!-- Argon Scripts -->
    <?php require_once('partials/_scripts.php'); ?>


  <div class="modal fade" id="DatabaseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Export to Excel</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <div class="modal-body">
          <form action="PHPSQL.php" method="post"  enctype="multipart/form-data">
           <div class="form-group">
           <label for="excel-file">Select an Excel file to read:</label>
           <input type="file" class = "form-control" id="excel-file" name="excel-file">
           </div>
            <hr>
            <button type="submit" name = "sql" class="btn btn-success"><i class="fa fa-check fa-fw"></i>Export</button>
          </form>  
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="PDFModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Export to Excel</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span>
          </button>
        </div>
        <div class="modal-body">
          <form action="PHPPDF.php" method="post" >
           <div class="form-group">
            <label>Before exporting the report, make sure to load the excel file first to database by clicking 'Export Report to Database'  </label>
            <label>Select report you want to export</label>
            <select name="category" id="gender"  class="form-control">
            <option value="Today">Today's Sales</option>
            <option value="Weekly">Weekly Sales</option>
            <option value="Monthly">Monthly Sales</option>
            <option value="Yearly">Yearly Sales</option>
            <option value="Buyer">Customer's Information</option>
            <option value="Staff">Staff's Information</option>
            <option value="Product">Product's Information</option>
            <option value="Suppliers">Supplier's Information</option>
            <option value="Transaction">Transactions</option>
            
            </select>
           </div>
            <hr>
            <button type="submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i>Export</button>
          </form>  
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel">View Transaction</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="transaction-details">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
  // when the view-transaction button is clicked
  $('.view-transaction').click(function() {
    // get the buyer_id from the data-buyer-id attribute
    var transaction_code = $(this).data('transaction_code');

    transaction_code = encodeURIComponent(transaction_code);

    // make an AJAX call to fetch the transaction details
    $.ajax({
      url: 'get_transaction_details.php', // replace with the URL of your PHP file that fetches the transaction details
      method: 'post',
      data: { transaction_code: transaction_code }, // send the buyer_id to the PHP file
      success: function(response) {
        // display the transaction details in the modal
        $('#transaction-details').html(response);
      },
      error: function() {
        // handle the error
        $('#transaction-details').html('Failed to fetch transaction details.');
      }
    });
  });
});
</script>


</body>

</html>