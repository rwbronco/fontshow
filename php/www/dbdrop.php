<?php
// THIS SCRIPT WILL DROP ALL TABLES FROM THE FONTSHOWDB. THIS IS FOR TESTING PURPOSES!

// Requires config.php for username variables
$config = require 'config.php';

// Database credentials from config.php
$dbhost = 'fontshow-mysql';
$dbname = $config['db']['dbname'];
$dbuser = $config['db']['username'];
$dbpass = $config['db']['password'];

// Connect to the database
$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all table names from the database
$tableQuery = "SHOW TABLES";
$result = mysqli_query($connect, $tableQuery);

if (!$result) {
    die("Error fetching tables: " . mysqli_error($connect));
}

// Drop each table
while ($row = mysqli_fetch_array($result)) {
    $table = $row[0];
    $dropQuery = "DROP TABLE IF EXISTS `$table`";
    if (!mysqli_query($connect, $dropQuery)) {
        echo "Error dropping table $table: " . mysqli_error($connect) . "<br>";
    } else {
        echo "Dropped table $table<br>";
    }
}

// Close the connection
mysqli_close($connect);

echo "All tables have been deleted.";
?>
