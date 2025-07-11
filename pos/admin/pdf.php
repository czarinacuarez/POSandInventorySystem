<!DOCTYPE html>
<html>
<head>
    <title>Export to PDF</title>
</head>
<body>

    <div>
  <img src="Christy.png" alt="Christy Store" width="150" height="150">
  <h1>Christy Store</h1>
  <h3>Database Table</h3>
</div>

    <?php
    // Connect to database
    $conn = mysqli_connect('localhost', 'root', '', 'mydb');

    // Create table
    $sql = "CREATE TABLE IF NOT EXISTS mytable (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
    mysqli_query($conn, $sql);

    // Insert data
    $sql = "INSERT INTO mytable (firstname, lastname, email)
            VALUES ('John', 'Doe', 'john@example.com')";
    mysqli_query($conn, $sql);

    $sql = "INSERT INTO mytable (firstname, lastname, email)
            VALUES ('Jane', 'Doe', 'jane@example.com')";
    mysqli_query($conn, $sql);

    // Display table
    $sql = "SELECT * FROM mytable";
    $result = mysqli_query($conn, $sql);

    echo "<table>";
    echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Registration Date</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row["id"]."</td>";
        echo "<td>".$row["firstname"]."</td>";
        echo "<td>".$row["lastname"]."</td>";
        echo "<td>".$row["email"]."</td>";
        echo "<td>".$row["reg_date"]."</td>";
        echo "</tr>";
    }
    echo "</table>";
    ?>

    <br>
    <form action="export.php" method="post">
        <input type="submit" name="export" value="Export to PDF">
    </form>
</body>
</html>
