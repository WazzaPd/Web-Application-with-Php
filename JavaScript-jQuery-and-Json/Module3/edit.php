<?php
session_start();
if(! isset($_SESSION['name'])) die("Not logged in");
require_once 'pdo.php';

if( isset($_POST['cancel'])){
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :p_id");
$profile_id = $_GET['profile_id'];
$profile_id = (int)trim($profile_id, '"');
$stmt->execute(array(":p_id"=>($profile_id)));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id = :p_id");
$stmt->execute(array(":p_id"=>($profile_id)));
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
        header('Location: edit.php?profile_id='.$profile_id);
        return;
    } else if (strpos($_POST['email'], '@') === false){
        $_SESSION['error'] = "Email address must contain @";
        header('Location: edit.php?profile_id='.$profile_id);
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
            ':p_id' => $profile_id)
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

<link rel="stylesheet" 
    href="style.css" >

<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
  
  <script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>

  <script src="position.js"></script>
  <script src="education.js"></script>
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


echo '<pre>';
var_dump($_POST);
echo '</pre>';
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
    <div style="display: block">
        <label for="education_button">Education:</label>
        <input type="submit" name="education_button" id="education_button" value="+">
        <!-- Container to hold added educations-->
        <div id="education_container"></div>
    </div>
    <div style="display: block">
        <label for="position_button">Position:</label>
        <input type="submit" name="position_button" id="position_button" value="+">
        <!-- Container to hold added positions-->
        <div id="position_container"></div>
    </div>
    <input type="submit" name="add" id="add" value="Add">
    <input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>