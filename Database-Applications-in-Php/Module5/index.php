<?php
    session_start();
    require_once 'pdo.php';

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
<h1>Welcome to Autos Database</h1>
<?php
//messages for user
if ( isset($_SESSION['error']) ) {
    echo '<pre>';
    echo var_dump($_SESSION);
    echo '</pre>';

    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
?>
<?php
if (! isset($_SESSION['login'])){
    echo'<p>';
    echo '<a href="login.php">Please log in</a>';
    echo '</p>';
} else {
    if( $rows !== []){
        echo '<table border=1>';
        foreach ($rows as $row){
            echo ('<tr><td style="padding: 2px">'.htmlentities($row['make']).'</td>');
            echo ('<td style="padding: 2px">'.htmlentities($row['model']).'</td>');
            echo ('<td style="padding: 2px">'.htmlentities($row['year']).'</td>');
            echo ('<td style="padding: 2px">'.htmlentities($row['mileage']).'</td>');
            echo '<td style="padding: 2px"><a href="edit.php?autos_id='.$row['autos_id'].'">edit</a>/';
            echo '<a href="delete.php?autos_id='.$row['autos_id'].'">delete</a>';
            echo '</td></tr>';
        }
        echo '</table>';
    } else echo '<p>No rows found</p>';
    echo '<a href="add.php">Add New Entry</a> | ';
    echo '<a href="logout.php">Logout</a>';
}
?>
</div>
</body>

