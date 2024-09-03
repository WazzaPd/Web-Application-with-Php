<?php
    session_start();
    require_once 'pdo.php';

$stmt = $pdo->query("SELECT * FROM Profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<h1>Joseph Wu's Resume Regristry</h1>
<?php
//messages for user
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
?>
<?php
if (! isset($_SESSION['name'])){
    echo'<p>';
    echo '<a href="login.php">Please log in</a>';
    echo '</p>';
} else {
    echo '<div style="display: block"><a href="logout.php">Logout</a></div>';
    if( $rows !== []){
        echo '<table border=1>';
        foreach ($rows as $row){
            echo ('<tr><td style="padding: 2px"><a href=view.php?profile_id="'.htmlentities($row['profile_id']).'">'.htmlentities($row['first_name'].$row['last_name']).'</a></td>');
            echo ('<td style="padding: 2px">'.htmlentities($row['headline']).'</td>');
            echo '<td style="padding: 2px"><a href="edit.php?profile_id='.$row['profile_id'].'">edit</a>/';
            echo '<a href="delete.php?profile_id='.$row['profile_id'].'">delete</a>';
            echo '</td></tr>';
        }
        echo '</table>';
    }
    echo '<div style="display: block"><a href="add.php">Add New Entry</a></div>';
}
?>
</div>
</body>

