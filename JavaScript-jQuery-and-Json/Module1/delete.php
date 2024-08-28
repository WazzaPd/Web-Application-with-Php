<?php
session_start();
if(! isset($_SESSION['name'])) die("Not logged in");
require_once 'pdo.php';

if( isset($_POST['cancel'])){
    header('Location: index.php');
    return;
}

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

if( isset($_POST['delete'])){
    $stmt = $pdo->prepare(
        'DELETE FROM profile WHERE profile_id=:p_id');
    $stmt->execute(array(':p_id' => $value));
    $_SESSION['success'] = 'Record updated';
    header('Location: index.php');
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
Confirm: Deleting Profile
</h1>
<?php
    echo "<p> First Name: ";
    echo htmlentities($row['first_name']);
    echo "</p>";
    echo "<p> Last Name: ";
    echo htmlentities($row['last_name']);
    echo "</p>";
?>
<form method="post">
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>
