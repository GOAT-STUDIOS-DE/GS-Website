
document.getElementById('profileImage').addEventListener('click', function() {
    const dropdown = document.getElementById('dropdownMenu');
    // Toggle der Klasse 'show'
    dropdown.classList.toggle('show');
});

window.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdownMenu');
    const profileImage = document.getElementById('profileImage');

    if (event.target !== profileImage && !profileImage.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});
