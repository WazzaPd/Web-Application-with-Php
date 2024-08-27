<?php
session_start();
if(! isset($_SESSION['login'])) die("Not logged in");
require_once 'pdo.php';

if( isset($_POST['cancel'])){
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :id");
$stmt->execute(array(":id"=>$_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//check if valid id
if ($row === false) {
    $_SESSION['error'] = "Bad value for id";
    header("Location: index.php");
    return;
}

if( isset($_POST['save'])){
    if(strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1){
        $_SESSION['error'] = 'All fields are required';
        header('Location: edit.php?autos_id='.$_GET['autos_id']);
        return;
    } 
    else if(! is_numeric($_POST['year'])){
        $_SESSION['error'] = "Year must be an integer";
        header('Location: edit.php?autos_id='.$_GET['autos_id']);
        return;
    } else if (! is_numeric($_POST['mileage']) ){
        $_SESSION['error'] = "Mileage must be an integer";
        header('Location: edit.php?autos_id='.$_GET['autos_id']);
        return;
    }
    //add to database
    else {
        $stmt = $pdo->prepare(
            'UPDATE autos SET
            make=:mk, model=:mo, 
            year=:yr, mileage=:mi
            WHERE autos_id=:id'
            );
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':mo' => $_POST['model'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage'],
            ':id' => $_GET['autos_id'])
        );
        $_SESSION['success'] = 'Record updated';
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
Editing Automobile
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
    <input type="text" name="make" id="make" <?php echo "value=".$row['make']?>>
</div>
<div style="display: block">
    <label for="model">Model:</label>
    <input type="text" name="model" id="model" <?php echo "value=".$row['model']?>>
</div>
<div style="display: block">
    <label for="year">Year:</label>
    <input type="text" name="year" id="year" <?php echo "value=".$row['year']?>>
</div>
<div style="display: block">
    <label for="mileage">Mileage:</label>
    <input type="text" name="mileage" id="mileage" <?php echo "value=".$row['mileage']?>>
</div>
<input type="submit" name="save" value="Save">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>
