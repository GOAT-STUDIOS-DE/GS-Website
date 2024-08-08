const apiUrl = 'https://goat-services.de/api/key.php';

        function fetchKey() {
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Netzwerkantwort war nicht ok.');
                    }
                    return response.json();
                })
                .then(data => {
                    const messageElement = document.getElementById('message');
                    const keyElement = document.getElementById('key');

                    if (data.key) {
                        keyElement.textContent = data.key;
                    } else if (data.error) {
                        messageElement.textContent = 'Fehler: ' + data.error;
                        keyElement.classList.add('error');
                    } else {
                        messageElement.textContent = 'Unbekannter Fehler.';
                        keyElement.classList.add('error');
                    }
                })
                .catch(error => {
                    const messageElement = document.getElementById('message');
                    messageElement.textContent = 'Fehler beim Abrufen des key: ' + error.message;
                    const keyElement = document.getElementById('key');
                    keyElement.classList.add('error');
                });
        }

        document.addEventListener('DOMContentLoaded', fetchKey);