<?php
session_start();
if(! isset($_SESSION['name'])) die("Not logged in");
require_once 'pdo.php';

$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :p_id");
$profile_id = $_GET['profile_id'];
$profile_id = (int)trim($profile_id, '"');
$stmt->execute(array(":p_id"=>($profile_id)));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id = :p_id");
$stmt->execute(array(":p_id"=>($profile_id)));
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

//check if valid id
if ($row === false) {
    $_SESSION['error'] = "Bad value for id";
    header("Location: index.php");
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
    Profile information
</h1>
<?php
    echo "<p> First Name: ";
    echo htmlentities($row['first_name']);
    echo "</p>";
    echo "<p> Last Name: ";
    echo htmlentities($row['last_name']);
    echo "</p>";
    echo "<p> Email: ";
    echo htmlentities($row['email']);
    echo "</p>";
    echo "<p> Headline: </br>";
    echo htmlentities($row['headline']);
    echo "</p>";
    echo "<p> Summary: </br>";
    echo htmlentities($row['summary']);
    echo "</p>";

    echo '<ul>';
    foreach ($positions as $position){
        // $positions is a 2D array
        if(is_array($position)){
            echo '<li>'.$position['year'].': '.$position['description'].'</li>';
        }
        // $positions is a 1D array
        else {
            echo '<li>'.$positions['year'].': '.$positions['description'].'</li>';
            break;
        }
    }
    echo '</ul>'
?>

<a href="index.php">Done</a>
</div>
</body>
</html>
