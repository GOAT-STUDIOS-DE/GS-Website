const canvas = document.getElementById('gameCanvas');
const context = canvas.getContext('2d');
const gridSize = 20;
const canvasSize = canvas.width;
const cells = canvasSize / gridSize;

let snake = [{x: 10, y: 10}];
let direction = {x: 0, y: 0};
let gems = [];
let obstacles = [];
let enemies = [];
let powerUps = [];
let score = 0;
let lives = 3;
let gameSpeed = 100;
let gameInterval;
let inBattle = false;

const gameScreen = document.getElementById('game-screen');
const battleScreen = document.getElementById('battle-screen');
const attackButton = document.getElementById('attack-button');
const runButton = document.getElementById('run-button');

function createGems() {
    while (gems.length < 5) {
        let gem = {x: Math.floor(Math.random() * cells), y: Math.floor(Math.random() * cells)};
        if (!isCellOccupied(gem)) {
            gems.push(gem);
        }
    }
}

function createObstacles() {
    while (obstacles.length < 10) {
        let obstacle = {x: Math.floor(Math.random() * cells), y: Math.floor(Math.random() * cells)};
        if (!isCellOccupied(obstacle)) {
            obstacles.push(obstacle);
        }
    }
}

function createEnemies() {
    while (enemies.length < 3) {
        let enemy = {x: Math.floor(Math.random() * cells), y: Math.floor(Math.random() * cells)};
        if (!isCellOccupied(enemy)) {
            enemies.push(enemy);
        }
    }
}

function isCellOccupied(cell) {
    return snake.some(segment => segment.x === cell.x && segment.y === cell.y) ||
           gems.some(gem => gem.x === cell.x && gem.y === cell.y) ||
           obstacles.some(obstacle => obstacle.x === cell.x && obstacle.y === cell.y) ||
           enemies.some(enemy => enemy.x === cell.x && enemy.y === cell.y);
}

function drawCell(cell, color) {
    context.fillStyle = color;
    context.fillRect(cell.x * gridSize, cell.y * gridSize, gridSize, gridSize);
}

function draw() {
    context.clearRect(0, 0, canvasSize, canvasSize);
    
    snake.forEach(segment => drawCell(segment, 'green'));
    gems.forEach(gem => drawCell(gem, 'red'));
    obstacles.forEach(obstacle => drawCell(obstacle, 'black'));
    enemies.forEach(enemy => drawCell(enemy, 'purple'));
    powerUps.forEach(powerUp => drawCell(powerUp, 'blue'));
    
    context.fillStyle = 'black';
    context.fillText(`Score: ${score}`, 10, 10);
    context.fillText(`Lives: ${lives}`, 10, 30);
}

function update() {
    if (inBattle) return;

    const head = {x: snake[0].x + direction.x, y: snake[0].y + direction.y};
    
    if (head.x < 0 || head.x >= cells || head.y < 0 || head.y >= cells || 
        obstacles.some(obstacle => obstacle.x === head.x && obstacle.y === head.y)) {
        lives--;
        if (lives === 0) {
            clearInterval(gameInterval);
            alert('Game Over');
        }
        return;
    }
    
    snake.unshift(head);
    
    const gemIndex = gems.findIndex(gem => gem.x === head.x && gem.y === head.y);
    if (gemIndex !== -1) {
        gems.splice(gemIndex, 1);
        score += 10;
        createGems();
    } else {
        snake.pop();
    }
    
    const enemyIndex = enemies.findIndex(enemy => enemy.x === head.x && enemy.y === head.y);
    if (enemyIndex !== -1) {
        inBattle = true;
        switchToBattleScreen();
    }
    
    if (lives === 0) {
        clearInterval(gameInterval);
        alert('Game Over');
    }
}

function switchToBattleScreen() {
    clearInterval(gameInterval);
    gameScreen.style.display = 'none';
    battleScreen.style.display = 'block';
}

function switchToGameScreen() {
    battleScreen.style.display = 'none';
    gameScreen.style.display = 'block';
    gameInterval = setInterval(gameLoop, gameSpeed);
}

function handleAttack() {
    inBattle = false;
    enemies.pop(); // Defeat one enemy
    setTimeout(switchToGameScreen, 3000); // Wait for 3 seconds before switching back to the game screen
}

function handleRun() {
    inBattle = false;
    switchToGameScreen();
}

attackButton.addEventListener('click', handleAttack);
runButton.addEventListener('click', handleRun);

function gameLoop() {
    update();
    draw();
}

document.addEventListener('keydown', (event) => {
    if (inBattle) return;

    switch(event.key) {
        case 'ArrowUp':
            if (direction.y === 0) direction = {x: 0, y: -1};
            break;
        case 'ArrowDown':
            if (direction.y === 0) direction = {x: 0, y: 1};
            break;
        case 'ArrowLeft':
            if (direction.x === 0) direction = {x: -1, y: 0};
            break;
        case 'ArrowRight':
            if (direction.x === 0) direction = {x: 1, y: 0};
            break;
    }
});

createGems();
createObstacles();
createEnemies();
gameScreen.style.display = 'block';
gameInterval = setInterval(gameLoop, gameSpeed);
