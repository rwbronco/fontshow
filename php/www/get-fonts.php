<?php 
require_once 'vendor/autoload.php';
use FontLib\Font;
// Load configuration
$config = require 'config.php';

// Extract database credentials
$host = 'fontshow-mysql'; // Change if necessary, should work out of the box
$dbname = $config['db']['dbname']; // DON'T CHANGE ANYTHING HERE, CHANGE DBNAME IN CONFIG.PHP
$username = $config['db']['username']; // DON'T CHANGE ANYTHING HERE, CHANGE USERNAME IN CONFIG.PHP
$password = $config['db']['password']; // DON'T CHANGE ANYTHING HERE, CHANGE PASSWORD IN CONFIG.PHP

$fontFolder = 'fonts/';

// Establish a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a table to store font metadata if it doesn't exist
$tableQuery = "CREATE TABLE IF NOT EXISTS fonts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL UNIQUE,
    full_name VARCHAR(255),
    subfamily VARCHAR(255),
    font_name VARCHAR(255),
    font_version VARCHAR(255),
    font_weight VARCHAR(255),
    postscript_name VARCHAR(255),
    folder_path VARCHAR(255),
    error TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4;";

if (!$conn->query($tableQuery)) {
    die("Error creating table: " . $conn->error);
}

// Refresh logic
if (isset($_GET['refresh'])) {
    $conn->query("TRUNCATE TABLE fonts");
}

// Recursive function to scan directories
function scanFontFiles($directory) {
    $files = [];
    $items = array_diff(scandir($directory), array('..', '.'));

    foreach ($items as $item) {
        $path = $directory . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            $files = array_merge($files, scanFontFiles($path));
        } elseif (preg_match('/\.(ttf|otf)$/', $item)) {
            $files[] = $path;
        }
    }

    return $files;
}

// Fetch all font files including subfolders
$fontFiles = scanFontFiles($fontFolder);

foreach ($fontFiles as $filePath) {
    $fileName = basename($filePath);
    $folderPath = dirname($filePath);

    // Check if the font file is already in the database
    $checkQuery = $conn->prepare("SELECT id FROM fonts WHERE file_name = ?");
    $checkQuery->bind_param("s", $fileName);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        $checkQuery->close();
        continue; // Skip processing
    }
    $checkQuery->close();

    if (!file_exists($filePath)) {
        error_log("Font file $filePath not found.");
        continue;
    }

    try {
        $font = Font::load($filePath);
        $font->parse();

        // Retrieve font metadata
        $fullName = $font->getFontFullName();
        $subfamily = $font->getFontSubfamily();
        $fontName = $font->getFontName();
        $fontVersion = $font->getFontVersion();
        $fontWeight = $font->getFontWeight();
        $postscriptName = $font->getFontPostscriptName();

        if (empty($fullName) || empty($subfamily)) {
            error_log("Metadata missing for font: $fileName");
        }

        // Insert metadata into the database
        $stmt = $conn->prepare("INSERT INTO fonts (file_name, full_name, subfamily, font_name, font_version, font_weight, postscript_name, folder_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $fileName, $fullName, $subfamily, $fontName, $fontVersion, $fontWeight, $postscriptName, $folderPath);
        $stmt->execute();
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Error processing font $fileName: $error");
        $stmt = $conn->prepare("INSERT INTO fonts (file_name, error, folder_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fileName, $error, $folderPath);
        $stmt->execute();
    }
}

// Retrieve all font data from the database
$result = $conn->query("SELECT file_name, full_name, subfamily, font_name, font_version, font_weight, postscript_name, folder_path, error FROM fonts");
if ($result) {
    $fontData = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($fontData);
} else {
    echo json_encode(["error" => "Failed to fetch font data: " . $conn->error]);
}

// Close the database connection
$conn->close();
?>
