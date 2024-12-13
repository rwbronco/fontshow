<?php
// THIS SCRIPT WILL DISPLAY CONTENTS OF ALL TABLES FROM THE FONTSHOWDB IN A SIMPLE TABLE. THIS IS FOR TESTING PURPOSES!

// Requires config.php for username variables
$config = require 'config.php';

// Database credentials from config.php
$dbhost = 'fontshow-mysql';
$dbname = $config['db']['dbname'];
$dbuser = $config['db']['username'];
$dbpass = $config['db']['password'];

$connect = mysqli_connect($dbhost, $dbuser, $dbpass) or die("Unable to Connect to '$dbhost'");
mysqli_select_db($connect, $dbname) or die("Could not open the db '$dbname'");

$test_query = "SHOW TABLES FROM $dbname";
$result = mysqli_query($connect, $test_query);

$tblCnt = 0;
while($tbl = mysqli_fetch_array($result)) {
  $tblCnt++;
  // Fetch table data
  $tableName = $tbl[0];
  echo "<h3>Contents of table: $tableName</h3>";

  // Query to get all data from the table
  $data_query = "SELECT * FROM $tableName";
  $data_result = mysqli_query($connect, $data_query);

  if (mysqli_num_rows($data_result) > 0) {
    // Start the table
    echo "<table border='1'><tr>";

    // Get and display column headers
    $fields = mysqli_fetch_fields($data_result);
    foreach ($fields as $field) {
      echo "<th>" . $field->name . "</th>";
    }
    echo "</tr>";

	// Output data of each row
	while ($row = mysqli_fetch_assoc($data_result)) {
	  echo "<tr>";
	  foreach ($row as $cell) {
		// Only call htmlspecialchars() if the cell is not null
		echo "<td>" . htmlspecialchars($cell ?? '') . "</td>";
	  }
	  echo "</tr>";
	}

    // End the table
    echo "</table><br />";
  } else {
    echo "No data found in this table.<br />";
  }
}

if (!$tblCnt) {
  echo "There are no tables<br />\n";
} else {
  echo "There are $tblCnt tables<br />\n";
}

mysqli_close($connect);
?>
