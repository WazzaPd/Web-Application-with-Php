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
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

//check if valid id
if ($row === false) {
    $_SESSION['error'] = "Bad value for id";
    header("Location: index.php");
    return;
}

//validate functions
function validatePos(){
    for($i=1; $i<=9; $i++){
        if (! isset($_POST['year'.$i])) continue;
        if (! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        if (strlen($year) == 0 || strlen($desc) == 0){
            return "All fields are required";
        }
        if ( ! is_numeric($year) ){
            return "Position year must be numeric";
        }
    }
    return true;
}

//checks for postion and education is in js files
if( isset($_POST['save'])){
    if(strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1){
        $_SESSION['error'] = 'All fields are required';
        header('Location: edit.php');
        return;
    } else if (strpos($_POST['email'], '@') === false){
        $_SESSION['error'] = "Email address must contain @";
        header('Location: add.php');
        return;
    } else if(validatePos() !== true){
        $_SESSION['error'] = validatePos();
        header('Location: add.php');
        return;
    }
    //add to database
    else {
        //delete old profile
        $stmt = $pdo->prepare('DELETE FROM Profile
        WHERE  profile_id=:pid');
        $stmt->execute(array(':pid' => $row['profile_id']));

        // add profile
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

        //add education

        //add positions
        $stmt = $pdo->prepare('SELECT profile_id FROM Profile WHERE user_id = :uid AND first_name = :fn AND last_name = :ln AND email=:em');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email']
        ));
        $profile_id = $stmt->fetchColumn();

        //add each position to database
        foreach ($_POST as $key => $value) {
            $confirm_add_position = false;
            if (strpos($key, 'year') !== false) {
                $rank = substr($key, -1);

                $year = (int)trim($value, '"');
                
            }
            else if(strpos($key, 'desc') !== false){
                $confirm_add_position = true;
                $description = $value;

            }

            if($confirm_add_position){
                //add to database
                $stmt = $pdo->prepare('INSERT INTO position
                (profile_id, year, rank, description)
                VALUES ( :pid, :yr, :rk, :desc )');
                $stmt->execute(array(
                    ':pid' => $profile_id,
                    ':yr' => $year,
                    ':rk' => $rank,
                    ':desc' => $description
                ));
            }
        }

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

  <script src="position.js"></script> <!-- Make sure this loads first -->
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
        <input type="text" name="first_name" id="first_name" value="<?php echo htmlentities($row['first_name']);?>">
    </div>
    <div style="display: block">
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" value="<?php echo htmlentities($row['last_name']);?>">
    </div>
    <div style="display: block">
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" value="<?php echo htmlentities($row['email']);?>">
    </div>
    <div style="display: block">
        <label for="headline">Headline:</label> </br>
        <input type="text" name="headline" id="headline" value="<?php echo htmlentities($row['headline']);?>">
    </div>
    <div style="display: block">
        <label for="summary">Summary:</label></br>
        <textarea name="summary" id="summary"> <?php echo htmlentities($row['summary']);?> </textarea>
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
        <div id="position_container">

        <?php
            foreach ($positions as $position){
                // $positions is a 2D array
                if(is_array($position)){
                    echo ('<div id="position'.$position['rank'].'" style="display: block">');
                    echo ('<label for="year'.$position['rank'].'">Year:</label>');
                    echo ('<input type="text" name="year'.$position['rank'].'" id="year'.$position['rank'].'" value="'.htmlentities($position['year']).'">');
                    echo ('<!-- remove current position section + cancel default button post -->');
                    echo ('<input type="button" name="removePosition'.$position['rank'].'" id="removePosition'.$position['rank'].'" class="removePosition" value="-"></br>');
                    echo ('<textarea name="desc'.$position['rank'].'" id="desc'.$position['rank'].'">'.htmlentities($position['description']).'</textarea>');
                    echo '</div>';
                }
                // $positions is a 1D array
                else {
                    echo ('<div id="position'.$positions['rank'].'" style="display: block">');
                    echo ('<label for="year'.$positions['rank'].'">Year:</label>');
                    echo ('<input type="text" name="year'.$positions['rank'].'" id="year'.$positions['rank'].'" value="'.htmlentities($positions['year']).'">');
                    echo ('<!-- remove current position section + cancel default button post -->');
                    echo ('<input type="button" name="removePosition'.$positions['rank'].'" id="removePosition'.$positions['rank'].'" class="removePosition" value="-"></br>');
                    echo ('<textarea name="desc'.$positions['rank'].'" id="desc'.$positions['rank'].'">'.htmlentities($positions['description']).'</textarea>');
                    echo '</div>';
                    break;
                }
            }
        ?>
        </div>
    </div>
    <input type="submit" name="save" value="Save">
    <input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>

</html>