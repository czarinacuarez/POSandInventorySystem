
<?php
    $dbuser="root";
    $dbpass="";
    $host="localhost";
    $db="main";
    $mysqli=new mysqli($host,$dbuser, $dbpass, $db);

    function prepared_query($mysqli, $sql, $params, $types = "")
    {
        $types = $types ?: str_repeat("s", count($params));
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt;
    }
?>