<?php
session_start();
if(! isset($_SESSION['login'])) die("Not logged in");
require_once 'pdo.php';

if( isset($_POST['cancel'])){
    header('Location: view.php');
    return;
}

if( isset($_POST['add'])){
    if(! (is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) ){
        $_SESSION['error'] = "Mileage and year must be numeric";
        header('Location: add.php');
        return;
    } else if(strlen($_POST['make'])<1){
        $_SESSION['error'] = "Make is required";
        header('Location: add.php');
        return;
    } 
    //add to database
    else {
        $stmt = $pdo->prepare('INSERT INTO autos
        (make, year, mileage) VALUES ( :mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage'])
        );
        $_SESSION['success'] = 'Record inserted';
        header('Location: view.php');
        return;
    }
}

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
?>
<form method="post">
<div style="display: block">
    <label for="make">Make:</label>
    <input type="text" name="make" id="make">
</div>
<div style="display: block">
    <label for="year">Year:</label>
    <input type="text" name="year" id="year">
</div>
<div style="display: block">
    <label for="mileage">Mileage:</label>
    <input type="text" name="mileage" id="mileage">
</div>
<input type="submit" name="add" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>
