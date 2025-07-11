<?php include_once("config/config.php");
if (isset($_POST['excel'])){
        $sql_query = "SELECT transaction_details.transactionD_id AS transaction_id , transaction_details.transaction_code , CONCAT(buyer.first_name ,' ' , buyer.last_name) as buyer ,  product.product_name , category.product_category , brand.brand_name AS product_brand , product.product_price AS product_price,  transaction_details.qty AS product_quantity , transaction_details.price as total, CONCAT(staff.sfirst_name ,' ' , staff.slast_name) as staff , transactions.date as date_bought FROM transactions INNER JOIN transaction_details ON transaction_details.transaction_code = transactions.transaction_code INNER JOIN staff ON transactions.staff_id = staff.staff_id INNER JOIN buyer ON buyer.buyer_id = transactions.buyer_id INNER JOIN product ON transaction_details.product_id = product.product_id INNER JOIN category ON product.category_id = category.category_id INNER JOIN brand ON brand.brand_id = product.brand_id;";
        $resultset = mysqli_query($mysqli, $sql_query) or die("database error:". mysqli_error($mysqli));
        $developer_records = array();
        while( $rows = mysqli_fetch_assoc($resultset) ) {
        $developer_records[] = $rows;
        }

        $filename = "Christy's_Store_".date('Ymd') . ".csv";            
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $show_coloumn = false;
        if(!empty($developer_records)) {
            foreach($developer_records as $record) {
                if(!$show_coloumn) {
                    echo implode(",", array_keys($record)) . "\n";
                    $show_coloumn = true;
                }
                echo implode(",", array_values($record)) . "\n";
            }
        }
}
exit;  
?>