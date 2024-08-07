// script.js
document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById('gameCanvas');
    const livesDisplay = document.getElementById('livesDisplay');
    const weaponDisplay = document.getElementById('weaponDisplay');

    // Erstelle den Spieler und die Waffe
    const player = document.createElement('div');
    player.className = 'player';
    const weapon = document.createElement('div');
    weapon.className = 'weapon';
    player.appendChild(weapon);
    canvas.appendChild(player);

    let bullets = [];
    let enemies = [];
    let enemyInterval = 2000; // Zeit in Millisekunden
    let lastEnemyTime = Date.now();
    let playerSpeed = 8; // Geschwindigkeit des Spielers
    let playerSpeedBoost = 16; // Geschwindigkeit mit Shift
    let playerPosition = { x: canvas.clientWidth / 2 - 15, y: canvas.clientHeight / 2 - 15 };
    let isSpeedBoost = false;
    let lives = 3;
    let keys = {}; // Objekt zum Speichern der gedrückten Tasten
    let lastShotTime = 0; // Zeitpunkt des letzten Schusses

    // Waffen-Konfigurationen
    const weapons = {
        shotgun: {
            name: 'Schrotflinte',
            cooldown: 1000, // Cooldown in Millisekunden
            range: 100, // Reichweite in Pixeln
            bulletSize: { width: 10, height: 2 },
            bulletSpeed: 3 // Geschwindigkeit der Kugel
        },
        sniper: {
            name: 'Sniper',
            cooldown: 1500, // Cooldown in Millisekunden
            range: 1000, // Reichweite in Pixeln
            bulletSize: { width: 10, height: 2 },
            bulletSpeed: 7 // Geschwindigkeit der Kugel
        },
        smg: {
            name: 'Maschinenpistole',
            cooldown: 50, // Cooldown in Millisekunden (0.05s)
            range: 300, // Reichweite in Pixeln
            bulletSize: { width: 10, height: 2 },
            bulletSpeed: 5 // Geschwindigkeit der Kugel
        }
    };

    let currentWeapon = 'shotgun'; // Startwaffe

    function updateLivesDisplay() {
        livesDisplay.textContent = `Leben: ${lives}`;
    }

    function updateWeaponDisplay() {
        weaponDisplay.textContent = `Waffe: ${weapons[currentWeapon].name}`;
    }

    function createEnemy() {
        const enemy = document.createElement('div');
        enemy.className = 'enemy';
        enemy.style.top = Math.random() * (canvas.clientHeight - 30) + 'px';
        enemy.style.left = Math.random() * (canvas.clientWidth - 30) + 'px';
        canvas.appendChild(enemy);
        enemies.push(enemy);
    }

    function moveEnemies() {
        enemies.forEach((enemy, eIndex) => {
            let rect = enemy.getBoundingClientRect();
            let canvasRect = canvas.getBoundingClientRect();
            let top = rect.top - canvasRect.top;
            let left = rect.left - canvasRect.left;

            // Bewegung der Gegner in Richtung Spieler
            let playerRect = player.getBoundingClientRect();
            let playerTop = playerRect.top - canvasRect.top + 15;
            let playerLeft = playerRect.left - canvasRect.left + 15;

            let dx = playerLeft - left;
            let dy = playerTop - top;
            let distance = Math.sqrt(dx * dx + dy * dy);

            if (distance > 1) {
                let moveX = (dx / distance) * 1; // Geschwindigkeit der Gegner
                let moveY = (dy / distance) * 1;
                top += moveY;
                left += moveX;
            }

            // Sicherstellen, dass die Gegner innerhalb des Canvas bleiben
            top = Math.max(0, Math.min(canvas.clientHeight - 30, top));
            left = Math.max(0, Math.min(canvas.clientWidth - 30, left));

            enemy.style.top = top + 'px';
            enemy.style.left = left + 'px';

            // Überprüfen, ob ein Gegner den Spieler berührt
            let playerRectUpdated = player.getBoundingClientRect();
            let enemyRectUpdated = enemy.getBoundingClientRect();
            if (playerRectUpdated.left < enemyRectUpdated.right &&
                playerRectUpdated.right > enemyRectUpdated.left &&
                playerRectUpdated.top < enemyRectUpdated.bottom &&
                playerRectUpdated.bottom > enemyRectUpdated.top) {
                // Spieler hat Schaden genommen
                lives--;
                canvas.removeChild(enemy);
                enemies.splice(eIndex, 1);
                if (lives <= 0) {
                    alert("Game Over!");
                    window.location.reload(); // Seite neu laden für einen Neustart
                }
                updateLivesDisplay();
            }
        });
    }

    function shootBullet(x, y, targetX, targetY) {
        const weapon = weapons[currentWeapon];
        const bullet = document.createElement('div');
        bullet.className = 'bullet';
        bullet.style.width = weapon.bulletSize.width + 'px';
        bullet.style.height = weapon.bulletSize.height + 'px';
        bullet.style.left = x + 'px';
        bullet.style.top = y + 'px';
        canvas.appendChild(bullet);
        bullets.push({ element: bullet, targetX, targetY, creationTime: Date.now(), speed: weapon.bulletSpeed });
    }

    function moveBullets() {
        bullets.forEach((bulletData, index) => {
            let bullet = bulletData.element;
            let rect = bullet.getBoundingClientRect();
            let canvasRect = canvas.getBoundingClientRect();
            let top = rect.top - canvasRect.top;
            let left = rect.left - canvasRect.left;

            // Berechnung der Bewegung der Kugel
            let dx = bulletData.targetX - (left + bulletData.element.clientWidth / 2);
            let dy = bulletData.targetY - (top + bulletData.element.clientHeight / 2);
            let distance = Math.sqrt(dx * dx + dy * dy);

            if (distance > 1) {
                let moveX = (dx / distance) * bulletData.speed;
                left += moveX; // Nur horizontale Bewegung
            }

            if (left < 0 || left > canvas.clientWidth) {
                canvas.removeChild(bullet);
                bullets.splice(index, 1);
                return;
            }

            bullet.style.top = top + 'px';
            bullet.style.left = left + 'px';

            enemies.forEach((enemy, eIndex) => {
                let enemyRect = enemy.getBoundingClientRect();
                let bulletRect = bullet.getBoundingClientRect();
                if (bulletRect.left < enemyRect.right &&
                    bulletRect.right > enemyRect.left &&
                    bulletRect.top < enemyRect.bottom &&
                    bulletRect.bottom > enemyRect.top) {
                    canvas.removeChild(enemy);
                    enemies.splice(eIndex, 1);
                    canvas.removeChild(bullet);
                    bullets.splice(index, 1);
                }
            });

            // Entfernen von Kugeln nach 2 Sekunden
            if (Date.now() - bulletData.creationTime > 2000) {
                canvas.removeChild(bullet);
                bullets.splice(index, 1);
            }
        });
    }

    function gameLoop() {
        moveEnemies();
        moveBullets();
        if (Date.now() - lastEnemyTime > enemyInterval) {
            createEnemy();
            lastEnemyTime = Date.now();
        }
        requestAnimationFrame(gameLoop);
    }

    function updatePlayerPosition() {
        player.style.left = playerPosition.x + 'px';
        player.style.top = playerPosition.y + 'px';
        // Update the weapon position
        weapon.style.top = '50%'; // Position der Waffe relativ zum Spieler
        weapon.style.left = '100%'; // Position der Waffe am rechten Rand des Spielers
    }

    function handleKeydown(event) {
        keys[event.key] = true;
        updatePlayerMovement();
    }

    function handleKeyup(event) {
        keys[event.key] = false;
        if (event.key === 'Shift') {
            isSpeedBoost = false;
        }
        updatePlayerMovement();
    }

    function handleKeydownShift(event) {
        if (event.key === 'Shift') {
            isSpeedBoost = true;
        }
    }

    function handleKeydownNumber(event) {
        if (event.key === '1') {
            currentWeapon = 'shotgun';
        } else if (event.key === '2') {
            currentWeapon = 'sniper';
        } else if (event.key === '3') {
            currentWeapon = 'smg';
        }
        updateWeaponDisplay(); // Waffe anzeigen aktualisieren
    }

    function updatePlayerMovement() {
        if (keys['w']) {
            playerPosition.y = Math.max(0, playerPosition.y - (isSpeedBoost ? playerSpeedBoost : playerSpeed));
        }
        if (keys['s']) {
            playerPosition.y = Math.min(canvas.clientHeight - 30, playerPosition.y + (isSpeedBoost ? playerSpeedBoost : playerSpeed));
        }
        if (keys['a']) {
            playerPosition.x = Math.max(0, playerPosition.x - (isSpeedBoost ? playerSpeedBoost : playerSpeed));
        }
        if (keys['d']) {
            playerPosition.x = Math.min(canvas.clientWidth - 30, playerPosition.x + (isSpeedBoost ? playerSpeedBoost : playerSpeed));
        }
        updatePlayerPosition();
    }

    function handleMouseMove(event) {
        let rect = canvas.getBoundingClientRect();
        let mouseX = event.clientX - rect.left;
        let mouseY = event.clientY - rect.top;
        // Auf das Schießen auf die Mausposition vorbereiten
        if (event.buttons === 1) { // Links-Klick (Maus-Taste 1)
            let currentTime = Date.now();
            const weapon = weapons[currentWeapon];
            if (currentTime - lastShotTime >= weapon.cooldown) {
                shootBullet(playerPosition.x + 15, playerPosition.y + 15, mouseX, mouseY);
                lastShotTime = currentTime;
            }
        }
    }

    window.addEventListener('keydown', handleKeydown);
    window.addEventListener('keydown', handleKeydownShift);
    window.addEventListener('keyup', handleKeyup);
    window.addEventListener('keydown', handleKeydownNumber); // Zum Waffenwechsel
    window.addEventListener('mousemove', handleMouseMove);

    updatePlayerPosition();
    updateLivesDisplay();
    updateWeaponDisplay(); // Waffe beim Start anzeigen
    gameLoop();
});
