<?php
$lines = file('users.html');

foreach ($lines as $line) {
    $user = json_decode($line, true);
    echo 'hello ' . $user['name'] . PHP_EOL;
}
?>