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

if( isset($_POST['save'])){
    if(strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1){
        $_SESSION['error'] = 'All fields are required';
        header('Location: edit.php?profile_id='.$value);
        return;
    } else if (strpos($_POST['email'], '@') === false){
        $_SESSION['error'] = "Email address must contain @";
        header('Location: edit.php?profile_id='.$value);
        return;
    }
    //add to database
    else {
        $stmt = $pdo->prepare(
            'UPDATE profile SET
            last_name=:ln, first_name=:fn, 
            email=:em, headline=:hd, summary=:sm
            WHERE profile_id=:p_id'
            );
        $stmt->execute(array(
            ':ln' => $_POST['last_name'],
            ':fn' => $_POST['first_name'],
            ':em' => $_POST['email'],
            ':hd' => $_POST['headline'],
            ':sm' => $_POST['summary'],
            ':p_id' => $value)
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
<title>Joseph Wu's Resume Regristry</title>
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
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name" value="<?php echo $row['first_name']?>">
</div>
<div style="display: block">
    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name" value="<?php echo $row['last_name']?>">
</div>
<div style="display: block">
    <label for="email">Email:</label>
    <input type="text" name="email" id="email" value="<?php echo $row['email']?>">
</div>
<div style="display: block">
    <label for="headline">Headline:</label> </br>
    <input type="text" name="headline" id="headline" value="<?php echo $row['headline']?>">
</div>
<div style="display: block">
    <label for="summary">Summary:</label></br>
    <textarea name="summary" id="summary"><?php echo $row['summary']?></textarea>
</div>
<input type="submit" name="save" value="Save">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>
