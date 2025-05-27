const lyrics = [
    "Let it be, let it be",
    "Is this the real life?",
    "Hello from the other side",
    "I got a feeling",
    "We will, we will rock you"
];

let lyric = "";

async function fetchLyric() {
    const res = await fetch('/backend/random-lyric.php');
    lyric = (await res.text()).trim().toLowerCase();
    document.getElementById('lyric-hint').textContent = `Guess the lyric! (${lyric.length} letters)`;
    document.getElementById('guess-history').innerHTML = ""; // Clear history on new lyric
    createInputBoxes();
}

function createInputBoxes() {
    const inputRow = document.getElementById('input-row');
    inputRow.innerHTML = '';
    for (let i = 0; i < lyric.length; i++) {
        const input = document.createElement('input');
        input.type = 'text';
        input.maxLength = 1;
        input.className = 'lyricle-box';
        input.autocomplete = 'off';
        input.dataset.index = i;

        // Typing a letter overrides and moves to next
        input.addEventListener('input', (e) => {
            const val = e.target.value;
            if (val.length > 1) {
                e.target.value = val.slice(-1);
            }
            if (val && i < lyric.length - 1) {
                inputRow.children[i + 1].focus();
                inputRow.children[i + 1].select();
            }
        });

        // Backspace and Enter handling
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace') {
                if (input.value === '' && i > 0) {
                    inputRow.children[i - 1].focus();
                    inputRow.children[i - 1].value = '';
                    e.preventDefault();
                }
            } else if (e.key === 'Enter') {
                submitGuess();
            } else if (e.key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) {
                // Allow typing to override
                input.value = '';
            }
        });

        inputRow.appendChild(input);
    }
}

// Reveal answer function
function revealAnswer() {
    const feedback = document.getElementById('feedback');
    feedback.textContent = `The answer was: "${lyric}"`;
}

function submitGuess() {
    if (!lyric) return;
    const inputs = document.querySelectorAll('#input-row .lyricle-box');
    const guess = Array.from(inputs).map(input => input.value.toLowerCase()).join('');
    const feedback = document.getElementById('feedback');

    if (guess.length !== lyric.length) {
        feedback.textContent = "Fill all boxes!";
        return;
    }

    // Prepare arrays for coloring
    const lyricArr = lyric.split('');
    const guessArr = guess.split('');
    const used = Array(lyric.length).fill(false);
    const boxColors = Array(lyric.length).fill('#787c7e'); // default gray

    // First pass: green for correct
    for (let i = 0; i < lyric.length; i++) {
        if (guessArr[i] === lyricArr[i]) {
            boxColors[i] = '#6aaa64'; // green
            used[i] = true;
        }
    }
    // Second pass: yellow for present but not correct place
    for (let i = 0; i < lyric.length; i++) {
        if (guessArr[i] !== lyricArr[i] && lyricArr.includes(guessArr[i])) {
            for (let j = 0; j < lyric.length; j++) {
                if (!used[j] && lyricArr[j] === guessArr[i] && guessArr[j] !== lyricArr[j]) {
                    boxColors[i] = '#c9b458'; // yellow
                    used[j] = true;
                    break;
                }
            }
        }
    }

    // Display guess in history
    const guessHistory = document.getElementById('guess-history');
    const guessRow = document.createElement('div');
    guessRow.style.display = 'flex';
    guessRow.style.marginBottom = '4px';
    for (let i = 0; i < guessArr.length; i++) {
        const box = document.createElement('div');
        box.textContent = guessArr[i] || ' ';
        box.className = 'lyricle-box';
        box.style.backgroundColor = boxColors[i];
        box.style.pointerEvents = 'none';
        guessRow.appendChild(box);
    }
    guessHistory.appendChild(guessRow);

    // Reset input boxes for next guess
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].style.backgroundColor = '';
        inputs[i].value = '';
    }
    if (inputs[0]) inputs[0].focus();

    if (guess === lyric) {
        feedback.textContent = "Correct! 🎉";
        // Optionally, disable further input here
    } else {
        feedback.textContent = "Try again!";
    }
}

window.onload = fetchLyric;