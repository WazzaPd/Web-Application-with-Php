<?php
session_start();
if(! isset($_SESSION['name'])) die("Not logged in");
require_once 'pdo.php';

if( isset($_POST['cancel'])){
    header('Location: index.php');
    return;
}

if( isset($_POST['add'])){
    if(strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1){
        $_SESSION['error'] = 'All fields are required';
        header('Location: add.php');
        return;
    } else if (strpos($_POST['email'], '@') === false){
        $_SESSION['error'] = "Email address must contain @";
        header('Location: add.php');
        return;
    }
    //add to database
    else {
        $stmt = $pdo->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary)
        VALUES ( :uid, :fn, :ln, :em, :he, :su)');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
    );
    $_SESSION['success'] = "Record Added";
    header("Location: index.php");
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
<?php
if ( isset($_SESSION['name']) ) {
    echo "Adding Profile for ";
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
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name">
</div>
<div style="display: block">
    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name">
</div>
<div style="display: block">
    <label for="email">Email:</label>
    <input type="text" name="email" id="email">
</div>
<div style="display: block">
    <label for="headline">Headline:</label> </br>
    <input type="text" name="headline" id="headline">
</div>
<div style="display: block">
    <label for="summary">Summary:</label></br>
    <textarea name="summary" id="summary"></textarea>
</div>
<input type="submit" name="add" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>
