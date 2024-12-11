<?php
// Include the configuration
$config = include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.php">
  <title><?php echo htmlspecialchars($config['title']); ?></title> <!-- CHANGE TITLE IN CONFIG -->
  
</head>

<body>
  <!-- Header and Preview Text Box Section -->
  <h1><?php echo htmlspecialchars($config['title']); ?></h1> <!-- CHANGE TITLE IN CONFIG -->
	<div class="input-container">
		<input 
		  id="text-input" 
		  type="text"
		  placeholder="Enter text to preview fonts..."
		  value="<?php echo htmlspecialchars($config['default_text']); ?>"
		>
		<div class="dropdown-container">
			<select id="subfamily-dropdown">
				<option value="All Subfamilies">All Subfamilies</option>
				<!-- Dynamic options will populate here -->
			</select>
			<span class="dropdown-arrow"></span>
		</div>
		
	</div>
  
  <!-- Call Div to Display Fonts List Section -->
  <div class="font-list" id="font-list"></div>
  
  <div class="cache-button-container">
  <button id="cache-btn">Refresh Cache</button>
  </div>

  <!-- Footer Section -->
  <footer class="footer">
    <a href=<?php echo htmlspecialchars($config['footer_link']); ?> class="footer-link"><?php echo htmlspecialchars($config['footer_text']); ?></a> <!-- CHANGE FOOTER IN CONFIG -->
    
  </footer>
  
  <!-- Font List Script -->
  <script>
// HTML elements
	const fontFolder = '/fonts/';
	const fontListDiv = document.getElementById('font-list');
	const textInput = document.getElementById('text-input');
	const subfamilyDropdown = document.getElementById('subfamily-dropdown'); // Dropdown element

	document.getElementById('cache-btn').addEventListener('click', () => {
		fetch('get-fonts.php?refresh=true')
			.then(response => response.json())
			.then(fonts => {
				console.log('Cache refreshed', fonts);
				location.reload(); // Reload page to reflect changes
			})
			.catch(error => console.error('Error refreshing cache:', error));
	});

	// Fetch font data and populate dropdown and font list
	fetch('get-fonts.php')
		.then(response => response.json())
		.then(fonts => {
			if (!Array.isArray(fonts)) {
				throw new Error("Invalid font data format");
			}

			// Extract unique subfamilies for dropdown options
			const subfamilies = [...new Set(fonts.map(font => font.subfamily || 'Unknown'))];

			// Populate the dropdown
			subfamilies.forEach(subfamily => {
				const option = document.createElement('option');
				option.value = subfamily;
				option.textContent = subfamily === 'Unknown' ? 'All Subfamilies' : subfamily;
				subfamilyDropdown.appendChild(option);
			});

			// Function to display fonts based on selected subfamily
			const displayFonts = (selectedSubfamily) => {
				fontListDiv.innerHTML = ''; // Clear existing fonts
				fonts
					.filter(font => selectedSubfamily === 'All Subfamilies' || font.subfamily === selectedSubfamily)
					.forEach((font, index) => {
						const fontFamilyName = `custom-font-${index}`;

						// Inject @font-face rule
						const styleSheet = document.styleSheets[0];
						styleSheet.insertRule(`
							@font-face {
								font-family: '${fontFamilyName}';
								src: url('${fontFolder}${font.file}');
							}
						`, styleSheet.cssRules.length);

						// Create a font display element
						const fontDiv = document.createElement('div');
						fontDiv.classList.add('font-display');
						fontDiv.style.fontFamily = fontFamilyName;

						fontDiv.innerHTML = `
							<p class="font-name">
								<a href="${fontFolder}${font.file}" download>${font.full_name}</a>
							</p>
							<p class="font-sample">${textInput.value || font.full_name}</p>
						`;

						fontListDiv.appendChild(fontDiv);
					});
			};

			// Initial font display
			displayFonts('All Subfamilies');

			// Update font display when dropdown selection changes
			subfamilyDropdown.addEventListener('change', (event) => {
				displayFonts(event.target.value);
			});

			// Update font samples when the text input changes
			textInput.addEventListener('input', () => {
				const newText = textInput.value || '';
				document.querySelectorAll('.font-sample').forEach(sample => {
					sample.textContent = newText;
				});
			});
		})
		.catch(error => console.error('Error fetching font data:', error));

  </script>
  
</body>

</html>
