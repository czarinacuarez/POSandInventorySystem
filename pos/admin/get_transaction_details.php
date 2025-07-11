<?php

include('config/config.php');

// get the transaction_code from the POST data
$transaction_code = urldecode($_POST['transaction_code']);

// select the transaction details from the SQL database using the transaction_code
$sql = "SELECT * FROM transactions INNER JOIN transaction_details ON transactions.transaction_code = transaction_details.transaction_code
INNER JOIN product ON product.product_id = transaction_details.product_id  WHERE transactions.transaction_code = '$transaction_code'";
$result = $mysqli->query($sql);



// display the transaction details in a table
if ($result->num_rows > 0) {

$sql2 = "SELECT * FROM  transactions LEFT JOIN buyer ON buyer.buyer_id = transactions.buyer_id
LEFT JOIN staff ON staff.staff_id = transactions.staff_id  WHERE transactions.transaction_code LIKE '$transaction_code'";
$result2 = $mysqli->query($sql2);
$row2 = $result2->fetch_object();
$cFirstName = $row2->first_name;
$cLastName = $row2->last_name;
$sFirstName = $row2->sfirst_name;
$sLastName = $row2->slast_name;
$date = $row2->date;

  echo '<table class="table">';
 

  echo '<thead class="thead-light">
  <tr>
  <td colspan="2"> Customer:</td> <td colspan="2" style = "text-align:right;"> '.  $cFirstName .' '.  $cLastName . ' </td>
  </tr>
  <tr>
  <td colspan="2"> Staff:</td> <td colspan="2" style = "text-align:right;"> '.  $sFirstName .' '.  $sLastName . ' </td>
  </tr>
  <tr>
  <th scope="col" >Product Name</th>
  <th scope="col" >Price</th>
  <th scope="col" >Quantity</th>
  <th scope="col" style = "text-align:right;">Total</th>
  </tr>
  </thead>';
  echo '<tbody>';
  while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['product_name'] . '</td>';
    echo '<td>' . $row['product_price'] . '</td>';
    echo '<td>' . $row['qty'] . '</td>';
    echo '<td style = "text-align:right;">' . $row['price'] . '</td>';
    echo '</tr>';
  }
  echo '</tbody></table>';
  echo '<table class="table">';
  echo '</tbody> <tr>';


  $cash = $row2->cash;
  $total = $row2->total_amt;
  $change = $cash - $total;

  echo '<br><tr>';
  echo '<th class = "text-success" colspan = "2" >Total</th><th class = "text-success" colspan = "2" style = "text-align:right;">' . $total . '</th>';
  echo '</tr>';
  

  echo '<th class = "text-info" colspan = "2" >Cash</th><td class = "text-info" colspan = "2" style = "text-align:right;">' . $cash . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th class = "text-success" colspan = "2">Change</th><td class = "text-success" colspan = "2" style = "text-align:right;">' . $change . '</td>';
  echo '</tr>';
  echo'  <tr>
  <th colspan = "2">Date:</th><td colspan="2" style = "text-align:right;"> '.  $date .' </td>
  </tr>';
  echo '</tbody></table>';
} else {
  echo 'No transaction details found.';
}






// close the database connection
$mysqli->close();
?>