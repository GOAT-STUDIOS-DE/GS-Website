document.addEventListener('DOMContentLoaded', () => {
    const options = document.querySelectorAll('.option');
    const sections = document.querySelectorAll('.content-section');

    // Zeigt die Profile-Sektion standardmäßig an
    const defaultSectionId = 'account'; // ID der Profile-Sektion
    sections.forEach(section => {
        if (section.id === defaultSectionId) {
            section.classList.add('active');
        } else {
            section.classList.remove('active');
        }
    });

    options.forEach(option => {
        option.addEventListener('click', () => {
            const targetId = option.getAttribute('data-target');

            sections.forEach(section => {
                if (section.id === targetId) {
                    section.classList.add('active');
                } else {
                    section.classList.remove('active');
                }
            });
        });
    });
});
