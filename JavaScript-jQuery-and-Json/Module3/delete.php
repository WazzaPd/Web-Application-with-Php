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
