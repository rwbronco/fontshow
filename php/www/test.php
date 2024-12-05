<?php
require_once 'vendor/autoload.php';

use FontLib\Font;

// Load the font
$font = Font::load('fonts/ariblk.ttf');

// Parse the font file
$font->parse();

// Retrieve and print font information
echo "Font Name: " . $font->getFontName() . "<br>";
echo "Font Subfamily: " . $font->getFontSubfamily() . "<br>";
echo "Font Full Name: " . $font->getFontFullName() . "<br>";
echo "Font Version: " . $font->getFontVersion() . "<br>";
echo "Font Weight: " . $font->getFontWeight() . "<br>";
echo "PostScript Name: " . $font->getFontPostscriptName() . "<br>";

// Close the font to release resources
$font->close();
