<?php

$expire = date('Y-m-d H:i:s', strtotime('- 1 hour'));
$curr = date('Y-m-d H:i:s');

echo $expire.PHP_EOL;
if($expire < $curr) {
    echo $expire . ' expired! ' . PHP_EOL;
} else {
    echo $curr . ' not expired! ' . PHP_EOL;
}


?>