        // Holen der Elemente
        const redirectBtns = document.querySelectorAll('.redirect-btn');
        const modal = document.getElementById('modal');
        const modalOverlay = document.getElementById('modal-overlay');
        const closeBtn = document.getElementById('close-btn');

        // Funktion zum Öffnen des Modals
        function openModal() {
            modal.classList.add('active');
            modalOverlay.classList.add('active');
        }

        // Funktion zum Schließen des Modals
        function closeModal() {
            modal.classList.remove('active');
            modalOverlay.classList.remove('active');
        }

        // Hinzufügen des Click-Events zu jedem Button
        redirectBtns.forEach(btn => {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); // Verhindert das Standardverhalten des Links
                openModal(); // Öffnet das Modal
            });
        });

        // Click-Event zum Schließen des Modals
        closeBtn.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', closeModal);