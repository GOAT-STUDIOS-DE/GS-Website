document.addEventListener('DOMContentLoaded', () => {
    // Alle Elemente mit der Klasse "hidden" finden und so
    const hiddenElements = document.querySelectorAll('.hidden');

    // Verzögerung für das Einblenden
    hiddenElements.forEach((element, index) => {
        setTimeout(() => {
            element.classList.add('fade-in');
            element.classList.remove('hidden');
        }, index * 150);
    });

    // Überprüfen, ob Discord verlinkt ist
    const overlayText = document.querySelector('.overlay-text');
    if (overlayText && overlayText.getAttribute('data-discord-linked') === 'true') {
        const notLinkedBox = document.getElementById('notlinked');
        if (notLinkedBox) {
            notLinkedBox.id = ''; // ID entfernen
        }
    }
});
