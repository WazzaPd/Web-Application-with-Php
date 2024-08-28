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
<?php require_once "bootstrap.php"; ?>
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

