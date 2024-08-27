<?php
session_start();
if(! isset($_SESSION['login'])) die("Not logged in");
require_once 'pdo.php';

if( isset($_POST['cancel'])){
    header('Location: index.php');
    return;
}

if( isset($_POST['add'])){
    if(strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1){
        $_SESSION['error'] = 'All fields are required';
        header('Location: add.php');
        return;
    } 
    else if(! is_numeric($_POST['year'])){
        $_SESSION['error'] = "Year must be an integer";
        header('Location: add.php');
        return;
    } else if (! is_numeric($_POST['mileage']) ){
        $_SESSION['error'] = "Mileage must be an integer";
        header('Location: add.php');
        return;
    }
    //add to database
    else {
        $stmt = $pdo->prepare('INSERT INTO autos
        (make, model, year, mileage) VALUES ( :mk, :mo, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':mo' => $_POST['model'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage'])
        );
        $_SESSION['success'] = 'Record added';
        header('Location: index.php');
        return;
    }
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
    <label for="model">Model:</label>
    <input type="text" name="model" id="model">
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
