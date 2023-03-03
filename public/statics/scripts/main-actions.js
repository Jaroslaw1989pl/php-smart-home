// top bar elements
const dropdownMenuToggle = document.getElementById('dropdown-toggle');
const dropdownMenu = document.getElementById('user-menu');


// open dropdown menu
dropdownMenuToggle.addEventListener('click', event => {
    event.preventDefault();
    dropdownMenu.style.display = 'block';
});

// closing dropdowns by click outside
window.onclick = event => {
    if (!event.target.matches('#dropdown-toggle'))
        if (dropdownMenu.style.display === 'block')
            dropdownMenu.style.display = 'none';

    if (!event.target.matches('#location-live-hints'))
        if (document.getElementById('location-live-hints'))
            document.getElementById('location-live-hints').innerHTML = '';
};