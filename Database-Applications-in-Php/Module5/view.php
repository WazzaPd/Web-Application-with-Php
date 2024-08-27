<?php
session_start();
if(! isset($_SESSION['login'])) die("Not logged in");
require_once 'pdo.php';

$stmt = $pdo->query("SELECT * FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>5d135b3f</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>
<?php
if ( isset($_SESSION['name']) ) {
    echo "Tracking Autos for ";
    echo htmlentities($_SESSION['name']);
}
?>
</h1>
<?php
//messages for user
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
?>
<h1>
    Automobiles
</h1>
<?php
    echo "<ul style='display: block'>";
    foreach ($rows as $row){
        echo "<li>";
        echo htmlentities($row['make']) ." ". htmlentities($row['year']) ." ". htmlentities($row['mileage']);
        echo "</li>";
    }
    echo "</ul>"
?>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</div>
</body>
</html>
