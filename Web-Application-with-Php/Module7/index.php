<!DOCTYPE html>
<head><title>Joseph Wu MD5 Cracker</title></head>
<body>
<h1>MD5 cracker</h1>
<p>This application takes an MD5 hash
of a two-character lower case string and 
attempts to hash all two-character combinations
to determine the original two characters.</p>
<pre>
Debug Output:
<?php
$goodtext = "Not found";

function maybeReallocate(&$recive, &$reallocating){
    if ($reallocating == 10) {
        $reallocating = 0; 
        $recive ++;
    }
}

// If there is no parameter, this code is all skipped
if ( isset($_GET['md5']) ) {
    $time_pre = microtime(true);
    $md5 = $_GET['md5'];

    $show = 15;
    $thousands = 0;
    $hundreds = 0;
    $tens = 0;
    $ones = 0;

    $start = '0000';

    //Brute Force Start
    while($start != '9999') {
        $start = $thousands.$hundreds.$tens.$ones;

        $checkHash = hash('md5', $start);

        if($md5 == $checkHash){
            $goodtext = $start; 
            break;
        }

        // Debug output until $show hits 0
        if ( $show > 0 ) {
            print "$checkHash $start\n";
            $show = $show - 1;
        }

        //Function maybeReallocate found at start of php code
        $ones++;
        maybeReallocate($tens, $ones);
        maybeReallocate($hundreds, $tens);
        maybeReallocate($thousands, $hundreds);
    }
    // Compute elapsed time
    $time_post = microtime(true);
    print "Elapsed time: ";
    print $time_post-$time_pre;
    print "\n";
}
?>
</pre>
<!-- Use the very short syntax and call htmlentities() -->
<p>Original PIN: <?= htmlentities($goodtext); ?></p>
<form>
<input type="text" name="md5" size="60" />
<input type="submit" value="Crack MD5"/>
</form>
<ul>
<li><a href="index.php">Reset</a></li>
<li><a href="md5.php">MD5 Encoder</a></li>
<li><a href="makecode.php">MD5 Code Maker</a></li>
<li><a
href="https://github.com/csev/wa4e/tree/master/code/crack"
target="_blank">Taken and Revised from this Source Code</a></li>
</ul>
</body>
</html>

