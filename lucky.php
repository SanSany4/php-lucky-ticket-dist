<?php

function add_zeroes($str) {   //additional zeroes in the begin
    $s = "$str";
    while (strlen($s) < 6) {
        $s = "0" . $s;
    }
    return $s;
}

function is_lucky($ticket) {                         //check if ticket is lucky
    $t = add_zeroes($ticket);
    $first = (int)$t[0] + (int)$t[1] + (int)$t[2];   //first and...
    $second = (int)$t[3] + (int)$t[4] + (int)$t[5];  //last three digits..
    return $first == $second;                        //compared
}

$sample = (int)$_POST['sample'];                     //size of distribution sample
$height = (int)$_POST['height'];                     //height of graphic / maximum height of bar
$width = (int)$_POST['width'];                       //width of single bar
$result = array();
$max = 0;                                            //maximum of lucky tickets in single sample between all samples
$count = 0;                                          //lucky tickets in current sample
$left = 0;                                           //left border of current sample
$right = $sample - 1;                                //right border of current sample
for($i = 0; $i < 1000000; ++$i) {
    if (is_lucky($i)) {
        ++$count;
    }
    if ($i == $right) {                              //if current sample is ended
        if ($count > $max) {                         //updating maximum
            $max = $count;
        }
        $result[] = array('left' => $left, 'right' => $right, 'count' => $count);//saving it
        $count = 0;                                  //resetting counter
        $left = $right + 1;                          //new borders
        $right = $left + $sample - 1;
    }
}
if($left <= 999999) {
    $result[] = ['left' => $left, 'right' => 999999, 'count' => $count];  //adding the last tickets out of sample
}

foreach ($result as $value) {
    $percent = 100 * $value['count'] / $max;
    $l = add_zeroes($value['left']);
    $r = add_zeroes($value['right']);
    $p = number_format($percent, 2);
    echo "$l .. $r: {$value['count']}  $p% <br>";    //outputting info about each sample
}
?>
<br>
<embed type="image/svg+xml">
    <?= '<?xml version="1.0 encoding="UTF-8"?>'; ?>
    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="<?=($width * count($result) + 20)?>" height="<?=$height?>">
        <?php
        //generating SVG
        $x = 10;    //offset
        foreach ($result as $value) {
            $h = (int)($height * $value['count'] / $max);  //relative height of column
            $y = $height - $h;
            echo "<rect x=\"$x\" y=\"$y\" width=\"$width\" height=\"$h\" fill=\"teal\"/>" . PHP_EOL; //drawing column
            $x += $width; //moving to next column
        }

        ?>
    </svg>
</embed>