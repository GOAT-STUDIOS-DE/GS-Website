document.addEventListener('DOMContentLoaded', () => {
    const options = document.querySelectorAll('.option');
    const sections = document.querySelectorAll('.content-section');

    // Zeigt die Account-Sektion standardmäßig an
    const defaultSectionId = 'account'; // ID der Account-Sektion
    document.getElementById(defaultSectionId).classList.add('active');

    options.forEach(option => {
        option.addEventListener('click', () => {
            const targetId = option.getAttribute('data-target');

            sections.forEach(section => {
                if (section.id === targetId) {
                    // Aktiviert die Sektion nur, wenn sie nicht bereits aktiv ist
                    if (!section.classList.contains('active')) {
                        sections.forEach(s => s.classList.remove('active')); // Deaktiviert alle anderen Sektionen
                        section.classList.add('active'); // Aktiviert die angeklickte Sektion
                    }
                }
            });
        });
    });
});
