<?php
header("Content-Type: text/css"); // Set the content type to CSS
$config = include('config.php'); // Load configuration
?>
body {
  font-family: Arial, sans-serif;
  padding: 20px;
  max-width: 1000px;
  margin: auto;
  min-height: 90vh; /* Ensures full-height layout */
  display: flex;
  flex-direction: column;
  background-color: <?php echo htmlspecialchars($config['background_color']); ?>; /* SET COLOR IN CONFIG.PHP */
}

/* Styling for the Site Title */
h1 {
  text-align: center;
  color: <?php echo htmlspecialchars($config['site_color']); ?>; /* SET COLOR IN CONFIG.PHP */
}

/* Styling for the Text Input and Refresh Button Parent Container */
.input-container {
  display: flex; /* Enables flexbox layout */
  align-items: center; /* Vertically centers items within the container */
  gap: 10px; /* Adds spacing between the input box and the button */
  margin-bottom: 40px;
}

/* Styling for the Text Preview Input Box */
#text-input {
  flex: 1; /* Makes the input box take up remaining space */
  padding: 10px;
  font-size: 16px;
  box-sizing: border-box;
  border-radius: 10px; /* Adds rounded corners */
  border: 1px solid #ccc;
}

/* Styling for the Refresh Cache button */
#refresh-cache-btn {
  background-color: <?php echo htmlspecialchars($config['site_color']); ?>; /* SET COLOR IN CONFIG.PHP */
  color: white;
  border: none;
  padding: 11px 15px;
  font-size: 14px;
  cursor: pointer;
  border-radius: 10px; /* Adds rounded corners */
}

#refresh-cache-btn:hover {
  background-color: <?php echo htmlspecialchars($config['secondary_color']); ?>; /* SET COLOR IN CONFIG.PHP */
}

/* Styling for the Font List Parent Flex Container */
.font-list {
  --gap: calc(<?php echo htmlspecialchars($config['font-display_columnspercent']); ?>% / 25); /* Declare box gap to also pass to child */
  flex: 0 0 100%;
  display: flex;
  width: calc(100% + var(--gap));
  flex-wrap: wrap;
  gap: var(--gap);
  
}

/* Styling for the Font List Child Flex Containers */
.font-display {
  flex: 0 0 calc(<?php echo htmlspecialchars($config['font-display_columnspercent']); ?>% - var(--gap)); /* SET COLUMNS IN CONFIG.PHP */
  border: 1px solid #ccc;
  padding: 10px;
  height: 130px;
  margin: 0 0 var(--gap) 0;
  box-sizing: border-box;
  border-radius: 10px; /* Adds rounded corners */
  overflow: hidden; /* Prevents content from overflowing */
}


/* Styling for the Font Name */
.font-name {
  white-space: nowrap; /* Prevents text from wrapping */
  overflow: hidden; /* Clips overflowing text */
  text-overflow: ellipsis; /* Adds ellipsis if text overflows */
  font-size: <?php echo htmlspecialchars($config['font-display_namesize']); ?>; /* CHANGE COLOR IN CONFIG */
  color: <?php echo htmlspecialchars($config['site_color']); ?>; /* CHANGE COLOR IN CONFIG */
  margin-bottom: 5px; /* Adds spacing below the font name */
  font-family: Arial, sans-serif;
}

.font-name a {
  text-decoration: none;
  color: inherit; /* Inherits color from .font-name */
}

.font-name a:hover {
  text-decoration: underline;
  color: <?php echo htmlspecialchars($config['secondary_color']); ?>;
}

/* Styling for the Font Preview Text */
.font-sample {
  white-space: nowrap; /* Prevents wrapping */
  overflow: hidden; /* Clips overflowing text */
  text-overflow: ellipsis; /* Adds ellipsis if text overflows */
  font-size: <?php echo htmlspecialchars($config['font-display_previewsize']); ?>; /* SET SIZE IN CONFIG */
  color: <?php echo htmlspecialchars($config['font-display_previewcolor']); ?>; /* SET COLOR IN CONFIG */
}

.footer {
  background-color: <?php echo htmlspecialchars($config['site_color']); ?>; /* Matches the blue used in the header */
  color: white;
  padding: 5px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: fixed; /* Ensures it stays at the bottom without causing scroll */
  bottom: 0;
  left: 0; /* Ensures it spans the full width */
  right: 0;
  width: 100%;
  box-sizing: border-box; /* Ensures padding is included in width */
}

.footer-link {
  color: white;
  text-decoration: none;
  font-size: 16px;
  text-align: center;
  flex-grow: 1;
  margin-left: auto;
  margin-right: auto;
}

.footer-link:hover {
  text-decoration: underline;
}