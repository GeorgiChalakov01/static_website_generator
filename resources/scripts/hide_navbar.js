let navbar = document.querySelector('.navbar');
let lastScrollTop = 0;

window.addEventListener('scroll', function() {
	let currentScrollTop = window.pageYOffset;
	if (currentScrollTop > lastScrollTop) {
		navbar.style.transform = "translateY(-100%)";
	} else {
		navbar.style.transform = "";
	}
	lastScrollTop = currentScrollTop;
});

document.querySelectorAll('.navbar a').forEach(function(link) {
	if (!link.classList.contains('dropdown-toggle') && !link.classList.contains('dropdown-item')) {
		link.addEventListener('click', function() {
			navbar.style.transform = "translateY(-100%)";
			setTimeout(function() {
				navbar.style.transform = "";
			}, 500);
		});
	}
});
