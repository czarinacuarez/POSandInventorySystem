<?php 
include_once("config/config.php");

// Create PDF file
require('fpdf.php');
$pdf = new FPDF('L', 'mm', 'A4'); // 'L' for landscape orientation
$pdf->AddPage();

// Add header with current date
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 30, date('Y-m-d'), 10, 0, 'R'); // Set current date in upper right corner
$pdf->Image('Christy.png', 10, 10, 75, 40);
$pdf->Cell(10, 20, '', 0, 0); 
$pdf->Cell(10, 10, '', 0, 1);
$pdf->Cell(30, 10, '', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, '', 0, 1);
$pdf->Ln(15);




$today = new DateTime();
$todaySQL = $today->format('Y-m-d');
$startOfWeek = $today->modify('this week')->modify('Monday')->format('Y-m-d');
$endOfWeek = $today->modify('this week +7 days')->format('Y-m-d');
$startOfMonth = $today->format('Y-m-01');
$endOfMonth = $today->modify('last day of this month')->format('Y-m-d');
$startOfYear = $today->format('Y-01-01');
$endOfYear = $today->format('Y-12-31');
$year = $today->format('Y');
$month = $today->format('m');
$monthName = strtoupper(DateTime::createFromFormat('!m', $month)->format('F'));




if (isset($_POST['category'])){

    $category = $_POST['category'];

    if ($category == "Weekly" ||  $category == "Monthly" ||$category == "Yearly"  || $category == "Today" ) {
        if($category == "Weekly") { 
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0,10,'THIS WEEK SALES REPORTS',0,1,'C');
            $select = "SELECT * FROM transactions WHERE date_bought >= ? AND date_bought <= ? ";
            $params = array($startOfWeek, $endOfWeek);
            $stmt = sqlsrv_query($conn, $select, $params);
        } else if ($category == "Monthly") {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0,10,'MONTH OF '.$monthName.' SALES REPORTS',0,1,'C');
            $select = "SELECT * FROM transactions WHERE date_bought >= ? AND date_bought <= ? ";
            $params = array($startOfMonth, $endOfMonth);
            $stmt = sqlsrv_query($conn, $select, $params);
        } else if ($category == "Yearly") {
            $pdf->SetFont('Arial', 'B', 12);
            $select = "SELECT * FROM transactions WHERE date_bought >= ? AND date_bought <= ? ";
            $pdf->Cell(0,10,'YEAR OF '. $year.' SALES REPORTS',0,1,'C');
            $params = array($startOfYear, $endOfYear);
            $stmt = sqlsrv_query($conn, $select, $params);
        } else if ($category == "Today"){
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0,10,'TODAY SALES REPORTS',0,1,'C');
            $select = "SELECT * FROM transactions WHERE date_bought >= ?";
            $params = array($todaySQL);
            $stmt = sqlsrv_query($conn, $select, $params);
        }
            // Add table header
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,10,'Code',1);
        $pdf->Cell(40,10,'Buyer',1);
        $pdf->Cell(25,10,'Item',1);
        $pdf->Cell(30,10,'Category',1);
        $pdf->Cell(30,10,'Brand',1);
        $pdf->Cell(20,10,'Price',1);
        $pdf->Cell(15,10,'Qty',1);
        $pdf->Cell(20,10,'Total',1);
        $pdf->Cell(40,10,'Staff',1);
        $pdf->Cell(25,10,'Date',1);
        $pdf->Ln();
        
        $pdf->SetFont('Arial','',10);
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $pdf->Cell(30,10,$row["transaction_code"],1);
            $pdf->Cell(40,10,$row["buyer"],1);
            $pdf->Cell(25,10,$row["product_name"],1);
            $pdf->Cell(30,10,$row["product_category"],1);
            $pdf->Cell(30,10,$row["product_brand"],1);
            $pdf->Cell(20,10,$row["product_price"],1);
            $pdf->Cell(15,10,$row["product_quantity"],1);
            $pdf->Cell(20,10,$row["product_total"],1);
            $pdf->Cell(40,10,$row["staff"],1);
            $pdf->Cell(25,10,$row["date_bought"]->format('Y-m-d'),1);
            $pdf->Ln();
        } 
    } else if ($category == "Buyer"){
        $sql = "SELECT * FROM  buyer INNER JOIN address ON buyer.address_id = address.address_id   INNER JOIN city ON address.city_id = city.city_id 
        INNER JOIN province ON address.province_id = province.province_id INNER JOIN street ON street.street_id = address.street_id ORDER BY buyer.last_name ASC";
        $result = mysqli_query($mysqli, $sql);
            
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0,10,'LIST OF CUSTOMERS INFORMATION',0,1,'C');
        
            $pdf->SetFont('Arial','B',10);
            
            $pdf->Cell(30,10,"Last Name",1);
            $pdf->Cell(30,10,"First Name",1);
            $pdf->Cell(25,10,"Gender",1);
            $pdf->Cell(35,10,"Contact",1);
            $pdf->Cell(15,10,"Age",1);
            $pdf->Cell(50,10,"Street",1);
            $pdf->Cell(45,10,"City",1);
            $pdf->Cell(45,10,"Province",1);
            $pdf->Ln();
        
            $pdf->SetFont('Arial','',10);

        while($row = mysqli_fetch_assoc($result)) {
            $pdf->Cell(30,10,$row["last_name"],1);
            $pdf->Cell(30,10,$row["first_name"],1);
            $pdf->Cell(25,10,$row["gender"],1);
            $pdf->Cell(35,10,$row["contact"],1);
            $pdf->Cell(15,10,$row["age"],1);
            $pdf->Cell(50,10,$row["street_name"],1);
            $pdf->Cell(45,10,$row["city_name"],1);
            $pdf->Cell(45,10,$row["province_name"],1);
            $pdf->Ln();
        }

    } else if ($category == "Staff"){
        $sql = "SELECT * FROM  staff INNER JOIN address ON staff.address_id = address.address_id   INNER JOIN city ON address.city_id = city.city_id 
        INNER JOIN province ON address.province_id = province.province_id INNER JOIN street ON street.street_id = address.street_id ORDER BY staff.slast_name ASC";
        $result = mysqli_query($mysqli, $sql);
            
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0,10,'LIST OF STAFFS INFORMATION',0,1,'C');
        
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(40,10,"Staff Code",1);
            $pdf->Cell(40,10,"Last Name",1);
            $pdf->Cell(40,10,"First Name",1);
            $pdf->Cell(30,10,"Gender",1);
            $pdf->Cell(40,10,"Contact",1);
            $pdf->Cell(30,10,"Age",1);
            $pdf->Cell(55,10,"Email",1);
            $pdf->Ln();
        
            $pdf->SetFont('Arial','',10);

        while($row = mysqli_fetch_assoc($result)) {
            $pdf->Cell(40,10,$row["staff_code"],1);
            $pdf->Cell(40,10,$row["slast_name"],1);
            $pdf->Cell(40,10,$row["sfirst_name"],1);
            $pdf->Cell(30,10,$row["gender"],1);
            $pdf->Cell(40,10,$row["contact"],1);
            $pdf->Cell(30,10,$row["age"],1);
            $pdf->Cell(55,10,$row["email"],1);
            $pdf->Ln();
        }
        
    }else if ($category == "Product"){
        $sql = "SELECT * FROM   product  LEFT JOIN  category ON product.category_id = category.category_id 
        LEFT JOIN brand ON brand.brand_id = product.brand_id LEFT JOIN supplier ON product.supplier_id = supplier.supplier_id" ;
        $result = mysqli_query($mysqli, $sql);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0,10,'LIST OF PRODUCTS INFORMATION',0,1,'C');

        $pdf->SetFont('Arial','B',10);

        $pdf->Cell(50,10,"Item Name",1);
        $pdf->Cell(50,10,"Item Description",1);
        $pdf->Cell(20,10,"Price",1);
        $pdf->Cell(40,10,"Category",1);
        $pdf->Cell(40,10,"Brand",1);
        $pdf->Cell(40,10,"Supplier",1);
        $pdf->Cell(40,10,"Item Added",1);
        $pdf->Ln();
    
        $pdf->SetFont('Arial','',10);

    while($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(50,10,$row["product_name"],1);
        $pdf->Cell(50,10,$row["product_description"],1);
        $pdf->Cell(20,10,$row["product_price"],1);
        $pdf->Cell(40,10,$row["product_category"],1);
        $pdf->Cell(40,10,$row["brand_name"],1);
        $pdf->Cell(40,10,$row["shop_name"],1);
        $pdf->Cell(40,10,$row["created_at"],1);
        $pdf->Ln();
    }
    }else if ($category == "Suppliers"){
        $sql = "SELECT * FROM  supplier INNER JOIN address ON supplier.address_id = address.address_id   INNER JOIN city ON address.city_id = city.city_id 
        INNER JOIN province ON address.province_id = province.province_id INNER JOIN street ON street.street_id = address.street_id ORDER BY supplier.last_name ASC";
        $result = mysqli_query($mysqli, $sql);
            
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0,10,'LIST OF SUPPLIERS INFORMATION',0,1,'C');
        
            $pdf->SetFont('Arial','B',10);
            
            $pdf->Cell(30,10,"Last Name",1);
            $pdf->Cell(30,10,"First Name",1);
            $pdf->Cell(30,10,"Shop Name",1);
            $pdf->Cell(18,10,"Gender",1);
            $pdf->Cell(25,10,"Contact",1);
            $pdf->Cell(10,10,"Age",1);
            $pdf->Cell(50,10,"Street",1);
            $pdf->Cell(45,10,"City",1);
            $pdf->Cell(40,10,"Province",1);
            $pdf->Ln();
        
            $pdf->SetFont('Arial','',10);

        while($row = mysqli_fetch_assoc($result)) {
            $pdf->Cell(30,10,$row["last_name"],1);
            $pdf->Cell(30,10,$row["first_name"],1);
            $pdf->Cell(30,10,$row["shop_name"],1);
            $pdf->Cell(18,10,$row["gender"],1);
            $pdf->Cell(25,10,$row["contact"],1);
            $pdf->Cell(10,10,$row["age"],1);
            $pdf->Cell(50,10,$row["street_name"],1);
            $pdf->Cell(45,10,$row["city_name"],1);
            $pdf->Cell(40,10,$row["province_name"],1);
            $pdf->Ln();
        }
    }else if ($category == "Transaction"){
        $sql = "SELECT * FROM  transactions LEFT JOIN buyer ON buyer.buyer_id = transactions.buyer_id
        LEFT JOIN staff ON staff.staff_id = transactions.staff_id ORDER BY `date` DESC  ";
        $result = mysqli_query($mysqli, $sql);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0,10,'TRANSACTIONS REPORT',0,1,'C');
    
        $pdf->SetFont('Arial','B',10);
        
        $pdf->Cell(30,10,"Code",1);
        $pdf->Cell(45,10,"Buyer's Name",1);
        $pdf->Cell(25,10,"No. of Items",1);
        $pdf->Cell(40,10,"Cash",1);
        $pdf->Cell(40,10,"Total Amount",1);
        $pdf->Cell(45,10,"Staff's Incharge",1);
        $pdf->Cell(50,10,"Date",1);
        $pdf->Ln();
    
        $pdf->SetFont('Arial','',10);

    while($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(30,10,$row["transaction_code"],1);
        $pdf->Cell(45,10,$row["last_name"] . ', ' . $row["first_name"],1);
        $pdf->Cell(25,10,$row["no_of_items"],1);
        $pdf->Cell(40,10,$row["cash"],1);
        $pdf->Cell(40,10,$row["total_amt"],1);
        $pdf->Cell(45,10,$row["slast_name"] . ', ' . $row["sfirst_name"],1);
        $date = new DateTime($row["date"]);
        $pdf->Cell(50,10,$date->format('Y-m-d'),1);
        $pdf->Ln();
    }
    }

$pdf->Output();

}

if (isset($_POST['print'])){
  
    $product_id = $_POST['print'];
    $product_name = $mysqli->query("SELECT product_name FROM product WHERE product_id = '$product_id' ")->fetch_object()->product_name; 
    $product_nameCaps = strtoupper($product_name);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0,10,$product_nameCaps . ' SALES REPORTS',0,1,'C');
    $select = "SELECT * FROM transactions WHERE product_name LIKE ?";
    $params = array($product_name);
    $stmt = sqlsrv_query($conn, $select, $params);

     // Add table header
     $pdf->SetFont('Arial','B',10);
     $pdf->Cell(30,10,'Code',1);
     $pdf->Cell(40,10,'Buyer',1);
     $pdf->Cell(25,10,'Item',1);
     $pdf->Cell(30,10,'Category',1);
     $pdf->Cell(30,10,'Brand',1);
     $pdf->Cell(20,10,'Price',1);
     $pdf->Cell(15,10,'Qty',1);
     $pdf->Cell(20,10,'Total',1);
     $pdf->Cell(40,10,'Staff',1);
     $pdf->Cell(25,10,'Date',1);
     $pdf->Ln();
     
     $pdf->SetFont('Arial','',10);
     while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
         $pdf->Cell(30,10,$row["transaction_code"],1);
         $pdf->Cell(40,10,$row["buyer"],1);
         $pdf->Cell(25,10,$row["product_name"],1);
         $pdf->Cell(30,10,$row["product_category"],1);
         $pdf->Cell(30,10,$row["product_brand"],1);
         $pdf->Cell(20,10,$row["product_price"],1);
         $pdf->Cell(15,10,$row["product_quantity"],1);
         $pdf->Cell(20,10,$row["product_total"],1);
         $pdf->Cell(40,10,$row["staff"],1);
         $pdf->Cell(25,10,$row["date_bought"]->format('Y-m-d'),1);
         $pdf->Ln();
     } 
     $pdf->Output();

}


if (isset($_POST['prints'])){
  
    $product_id = $_POST['prints'];
    $product_name = $mysqli->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM buyer WHERE buyer_id = '$product_id' ")->fetch_object()->name; 
    $product_nameCaps = strtoupper($product_name);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0,10,$product_nameCaps . ' TRANSACTION RECORDS',0,1,'C');
    $select = "SELECT * FROM transactions WHERE buyer LIKE ?";
    $params = array($product_name);
    $stmt = sqlsrv_query($conn, $select, $params);

     // Add table header
     $pdf->SetFont('Arial','B',10);
     $pdf->Cell(30,10,'Code',1);
     $pdf->Cell(40,10,'Buyer',1);
     $pdf->Cell(25,10,'Item',1);
     $pdf->Cell(30,10,'Category',1);
     $pdf->Cell(30,10,'Brand',1);
     $pdf->Cell(20,10,'Price',1);
     $pdf->Cell(15,10,'Qty',1);
     $pdf->Cell(20,10,'Total',1);
     $pdf->Cell(40,10,'Staff',1);
     $pdf->Cell(25,10,'Date',1);
     $pdf->Ln();
     
     $pdf->SetFont('Arial','',10);
     while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
         $pdf->Cell(30,10,$row["transaction_code"],1);
         $pdf->Cell(40,10,$row["buyer"],1);
         $pdf->Cell(25,10,$row["product_name"],1);
         $pdf->Cell(30,10,$row["product_category"],1);
         $pdf->Cell(30,10,$row["product_brand"],1);
         $pdf->Cell(20,10,$row["product_price"],1);
         $pdf->Cell(15,10,$row["product_quantity"],1);
         $pdf->Cell(20,10,$row["product_total"],1);
         $pdf->Cell(40,10,$row["staff"],1);
         $pdf->Cell(25,10,$row["date_bought"]->format('Y-m-d'),1);
         $pdf->Ln();
     } 
     $pdf->Output();

}



exit;  
?>