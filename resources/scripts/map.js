function load_map(x, y, width, height) {
	// Load the SVG file using Ajax
	fetch('images/world.svg')
		.then(response => response.text())
		.then(svgText => {
			// Create a new SVG element
			const parser = new DOMParser();
			const svgDoc = parser.parseFromString(svgText, 'image/svg+xml');
			const svg = svgDoc.querySelector('svg');

			// Set the SVG width and height to 100%
			svg.style.width = '100%';
			svg.style.height = '100%';

			// Set the viewBox attribute to show only a small part of the SVG
			// The values below are just examples, you can adjust them to show the desired part of the map
			// The viewBox attribute is in the format "x y width height"
			svg.setAttribute('viewBox', x + ' ' + y + ' ' + width + ' ' + height); // Show only the region around Europe and the Middle East

			// Append the SVG element to the page
			document.getElementById('svg-container').appendChild(svg);

			// Get all country paths
			const countries = svg.querySelectorAll('path');

			// Add event listeners for hover and click
			countries.forEach((country) => {
				country.addEventListener('mouseover', () => {
					country.style.fill = 'green';
				});

				country.addEventListener('mouseout', () => {
					country.style.fill = 'black';
				});

				country.addEventListener('click', () => {
					country.style.fill = 'red';
					console.log(`Country clicked: ${country.id}`);
				});
			});
		})
		.catch(error => console.error('Error loading SVG file:', error));
}
