<?php

$user = [
    'id' => 4,
    'name' => 'Dave'
];

$line = json_encode($user) . "\n";

file_put_contents('users.html', $line , FILE_APPEND);
?>