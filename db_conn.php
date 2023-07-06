<?php


$includePaths = [
    'C:\wamp64\db',   // Replace with the actual path to db.php
    get_include_path(),   // Retrieve the existing include path
];

// Set the updated include path
set_include_path(implode(PATH_SEPARATOR, $includePaths));


require 'db.php';

try {
    $conn = new PDO("mysql:host=$hostname;db_name=$db_name", $username, $password);
    $conn->exec("USE todo_list");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection Failed : " . $e->getMessage();
}
?>