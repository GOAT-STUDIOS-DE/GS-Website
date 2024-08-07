document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('message-form');
    const messagesContainer = document.getElementById('messages');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch('chat.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                form.reset();
                loadMessages();
            } else {
                alert('Fehler: ' + data.error);
            }
        });
    });

    function loadMessages() {
        fetch('chat.php')
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const messagesHTML = doc.getElementById('messages').innerHTML;
            messagesContainer.innerHTML = messagesHTML;
            messagesContainer.scrollTop = messagesContainer.scrollHeight; // Scrollen nach unten
        });
    }
    

    loadMessages(); // Initiales Laden der Nachrichten
    setInterval(loadMessages, 2000); // Nachrichten alle 2 Sekunden aktualisieren
});