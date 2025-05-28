const lyrics = [
    "Let it be, let it be",
    "Is this the real life?",
    "Hello from the other side",
    "I got a feeling",
    "We will, we will rock you"
];

let lyric = "";
let album = "";
let trackTitle = "";

// Fetch lyric, album, and track title for hints
async function fetchLyric() {
    const res = await fetch('/backend/random-lyric.php');
    const data = await res.json ? await res.json() : await res.text();
    if (typeof data === "string") {
        lyric = data.trim().toLowerCase();
        album = "";
        trackTitle = "";
    } else {
        lyric = (data.lyric || "").trim().toLowerCase();
        album = data.album || "";
        trackTitle = data.track_title || "";
    }
    document.getElementById('lyric-hint').textContent = `Guess the lyric! (${lyric.length} letters)`;
    document.getElementById('guess-history').innerHTML = "";
    document.getElementById('hint-info').textContent = "";
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

// Hint: Reveal first letter of each word in track title
function showHint() {
    if (!trackTitle) {
        document.getElementById('hint-info').textContent = "No track title info available.";
        return;
    }
    const firstLetters = trackTitle.split(/\s+/).map(w => w[0] ? w[0].toUpperCase() : '').join(' ');
    document.getElementById('hint-info').textContent = `Track title initials: ${firstLetters}`;
}

function submitGuess() {
    if (!lyric) return;
    const inputs = document.querySelectorAll('#input-row .lyricle-box');
    const guess = Array.from(inputs).map(input => input.value.toLowerCase()).join('');
    const feedback = document.getElementById('feedback');

    // Allow submitting if all non-space positions are filled
    let allFilled = true;
    for (let i = 0; i < lyric.length; i++) {
        if (lyric[i] !== " " && !inputs[i].value) {
            allFilled = false;
            break;
        }
    }
    if (!allFilled) {
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
    guessRow.className = 'guess-row';
    for (let i = 0; i < guessArr.length; i++) {
        const box = document.createElement('input');
        box.type = 'text';
        box.maxLength = 1;
        box.className = 'lyricle-box';
        box.value = guessArr[i] || ' ';
        box.style.backgroundColor = boxColors[i];
        box.readOnly = true;
        box.tabIndex = -1;
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