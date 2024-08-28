<?php
session_start();
if(! isset($_SESSION['name'])) die("Not logged in");
require_once 'pdo.php';

$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :p_id");
$value = $_GET['profile_id'];
$value = (int)trim($value, '"');
$stmt->execute(array(":p_id"=>($value)));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//check if valid id
if ($row === false) {
    $_SESSION['error'] = "Bad value for id";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Joseph Wu's Resume Regristry</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>
    Profile information
</h1>
<?php
    echo "<p> First Name: ";
    echo htmlentities($row['first_name']);
    echo "</p>";
    echo "<p> Last Name: ";
    echo htmlentities($row['last_name']);
    echo "</p>";
    echo "<p> Email: ";
    echo htmlentities($row['email']);
    echo "</p>";
    echo "<p> Headline: </br>";
    echo htmlentities($row['headline']);
    echo "</p>";
    echo "<p> Summary: </br>";
    echo htmlentities($row['summary']);
    echo "</p>";
?>

<a href="index.php">Done</a>
</div>
</body>
</html>
