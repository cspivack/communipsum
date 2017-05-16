riot.mount('*');

var toggle = document.getElementById('expand-toggle');

toggle.addEventListener('click', toggleSection);

function toggleSection(e) {

	e.preventDefault();

	var clicked = this;
	var target = document.getElementById(clicked.getAttribute('data-href'));
	var style = window.getComputedStyle(target);

	var animation,
		opening;

	if(style.display==='none') {
		animation = 'slideDown';
		opening = true;
	} else {
		animation = 'slideUp';
		opening = false;
	}

	Velocity(target, animation, {
		duration: 125,
		begin: function() {
			clicked.className = opening ? 'open' : '';
		}
	});

}