<?php
session_start();
if(! isset($_SESSION['login'])) die("Not logged in");
require_once 'pdo.php';

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :id");
$stmt->execute(array(":id"=>$_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//check if valid id
if ($row === false) {
    $_SESSION['error'] = "Bad value for id";
    header("Location: index.php");
    return;
}

if( isset($_POST['delete'])){
    $stmt = $pdo->prepare(
        'DELETE FROM autos WHERE autos_id=:id');
    $stmt->execute(array(':id' => $_GET['autos_id'])
    );
    $_SESSION['success'] = 'Record updated';
    header('Location: index.php');
    return;
}
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
Confirm: deleting <?php echo $row['make']?>
</h1>
<form method="post">
<input type="submit" name="delete" value="Delete">
<a href="index.php">Cancel</a>
</form>
</div>
</body>
</html>
