<?php

require_once 'pdo.php';

if (! isset($_GET['name']) || strlen($_GET['name']) < 1){
    die('Name parameter missing');
}

//logout
if( isset($_POST['logout'])){
    header('Location: index.php');
}

$failure = false;
$success = false;
if( isset($_POST['add'])){
    if(! (is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) ){
        $failure = "Mileage and year must be numeric";
    } else if(strlen($_POST['make'])<1){
        $failure = "Make is required";
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
        $success = 'Record inserted';
    }
}

$stmt = $pdo->query("SELECT * FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Joseph Wu</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>
<?php
if ( isset($_REQUEST['name']) ) {
    echo "Tracking Autos for ";
    echo htmlentities($_REQUEST['name']);
}
?>
</h1>
<?php
//messages for user
if ( $failure !== false ) {
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
if ( $success !== false ) {
    echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
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
<input type="submit" name="logout" value="Logout">
</form>

<h1>
    Automobiles
</h1>
<?php
    echo "<ul>";
    foreach ($rows as $row){
        echo "<li>";
        echo htmlentities($row['make']) ." ". htmlentities($row['year']) ." ". htmlentities($row['mileage']);
        echo "</li>";
    }
    echo "</ul>"
?>
</div>
</body>
</html>
