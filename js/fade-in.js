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
});
