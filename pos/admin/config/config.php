<?php
    $host = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db = "main";
    $mysqli = mysqli_connect($host, $dbuser, $dbpass, $db) or die("Connection failed: " . mysqli_connect_error());
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    
	$serverName = "MSI\SQLEXPRESS"; 
    $connectionInfo = array( "Database"=>"main");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);

    function prepared_query($mysqli, $sql, $params, $types = "")
    {
    $types = $types ?: str_repeat("s", count($params));
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt;
    }
?>