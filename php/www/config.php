<?php

// Configurable elements for FontShow

$columns = 3; // Number of columns to display fonts in

return [
    'title' => 'FontShow', // Site Title
	
    'default_text' => 'The quick brown fox jumps over the lazy dog.', // Default text for text preview
	
    'footer_text' => 'FontShow 2024', // Text for footer at bottom
	
	'footer_link' => 'https://github.com/rwbronco/fontshow', // Link for footer at bottom
	
	'site_color' => '#007BFF', // Main color used throughout the website
	
	'secondary_color' => '#0056b3', // Secondary color used for button mouseover
	
	'background_color' => '#F2F3F4', // Background color
	
	'font-display_columns' => $columns, // Set columns at top of config, (currently unused)
	
    'font-display_columnspercent' => 100 / $columns, // Calculates flex box percentage, DO NOT CHANGE
	
	'font-display_namesize' => '21px', // Sets the font size for Font Names
	
	'font-display_previewsize' => '16px', // Sets the font size for Font Previews
	
	'font-display_previewcolor' => '#313638', // Color used for Font Preview text
];
