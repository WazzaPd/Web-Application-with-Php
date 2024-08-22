<title>a4aeb551</title>

<?php
$correctNum = 50;

echo "<p>";
if (array_key_exists('guess', $_GET)){
    $guess = $_GET['guess'];

    if(is_numeric($guess)){
        $output = ($_GET['guess'] > $correctNum) ? "Your guess is too high"  : (( $_GET['guess'] < $correctNum) ? "Your guess is too low" : "Congratulations - You are right");
        echo $output;
    }
    else if(strlen($guess)==0) echo "Your guess is too short";
    else echo "Your guess is not a number";
}
else {
    echo "Missing guess parameter";
}
echo "</p>";
?>