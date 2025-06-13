<!-- index.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Card Duel</title>
  <style>
    body { font-family: sans-serif; text-align: center; }
    .card { border: 1px solid #333; padding: 10px; margin: 5px; display: inline-block; cursor: pointer; width: 180px; vertical-align: top; }
    .card img { width: 100%; height: 100px; object-fit: cover; }
    .card-name { font-weight: bold; }
    .card-desc { font-size: 0.9em; margin: 5px 0; }
    .card-meta { font-size: 0.8em; color: #555; }
    .rarity-Common    { border-color: #444; }
    .rarity-Uncommon  { border-color: #228B22; }
    .rarity-Rare      { border-color: #1E90FF; }
    .rarity-Epic      { border-color: #9400D3; 
-webkit-box-shadow:0px 0px 74px 0px rgba(154,46,255,0.13);
-moz-box-shadow: 0px 0px 74px 0px rgba(154,46,255,0.13);
box-shadow: 0px 0px 74px 0px rgba(154,46,255,0.13);
    }
    .rarity-Legendary { border-color: #FFD700; 
-webkit-box-shadow:0px 0px 74px 0px rgba(255,255,46,0.13);
-moz-box-shadow: 0px 0px 74px 0px rgba(255,255,46,0.13);
box-shadow: 0px 0px 74px 0px rgba(255,255,46,0.13);
}
    .rarity-Mythical  { border-color: #FF4500; 
-webkit-box-shadow:0px 0px 74px 0px rgba(255,46,46,0.13);
-moz-box-shadow: 0px 0px 74px 0px rgba(255,46,46,0.13);
box-shadow: 0px 0px 74px 0px rgba(255,46,46,0.13);
}
  </style>
</head>
<body>
  <h1>Card Duel</h1>
  <div id="status"></div>
  <div id="hp"></div>
  <div id="hand"></div>
  <div id="enemy"></div>
  <button id="skipBtn" onclick="skipTurn()" style="margin:10px 0;">Skip Turn (+5 Mana)</button>

  <script>
    const gameId = new URLSearchParams(location.search).get('game');
    let playerNum = null;
    let lastStateJSON = null;

    function getGameState(force = false) {
      fetch(`cards.php?action=state&game=${gameId}`)
        .then(res => res.json())
        .then(state => {
          const stateJSON = JSON.stringify(state);
          if (force || stateJSON !== lastStateJSON) {
            updateUI(state);
            lastStateJSON = stateJSON;
          }
        });
    }

    function playCard(index) {
      fetch(`cards.php?action=play&game=${gameId}&player=${playerNum}&card=${index}`)
        .then(() => getGameState(true)); // Force update after playing a card
    }

    function skipTurn() {
      fetch(`cards.php?action=skip&game=${gameId}`)
        .then(() => getGameState(true));
    }

    function updateUI(state) {
      playerNum = state.playerNum;
      if (state.waiting) {
        document.getElementById('status').innerText = "Waiting for another player to join...";
        document.getElementById('hand').innerHTML = '';
        document.getElementById('hp').innerHTML = '';
        document.getElementById('enemy').innerHTML = '';
        document.getElementById('skipBtn').style.display = "none";
        return;
      }

      // Show HP and Mana for both players
      document.getElementById('hp').innerText =
        `Your HP: ${state['player'+playerNum].hp} | Mana: ${state['player'+playerNum].mana} | Enemy HP: ${state['player'+(playerNum===1?2:1)].hp}`;

      if (state.over) {
        document.getElementById('status').innerText =
          state.winner == playerNum ? "You win!" : "You lose!";
        document.getElementById('hand').innerHTML = '';
        document.getElementById('enemy').innerHTML = '';
        document.getElementById('skipBtn').style.display = "none";
        return;
      }

      document.getElementById('status').innerText =
        state.turn !== playerNum ? "Waiting for opponent..." : "Your turn!";

      // Show skip button only on your turn
      document.getElementById('skipBtn').style.display = (state.turn === playerNum && !state.over) ? "inline-block" : "none";

      const hand = state[`player${playerNum}`].hand;
      const mana = state[`player${playerNum}`].mana;

      // Block HP logic
      const blockHP = state[`player${playerNum}`].block_hp && state[`player${playerNum}`].block_hp > 0;

      // Reveal hands logic
      const reveal = state.reveal_hands && state.reveal_hands > 0;

      // Show your hand
      document.getElementById('hand').innerHTML = '';
      hand.forEach((card, i) => {
        const div = document.createElement('div');
        const rarityClass = 'rarity-' + card.rarity.charAt(0) + card.rarity.slice(1).toLowerCase();
        div.className = 'card ' + rarityClass;
        const imgPath = '/' + card.image.replace(/\\/g, '/');
        div.innerHTML = `
          <img src="${imgPath}" alt="${card.name}">
          <div class="card-name">${card.name}</div>
          <div class="card-desc">${card.description}</div>
          <div class="card-meta ${rarityClass}">
            Type: ${card.type} | <span>Rarity: ${card.rarity}</span> | Mana: ${card.mana_cost}
          </div>
        `;
        // Only allow playing if it's your turn, game not over, you have enough mana, and not blocked from HP
        const canPlay = state.turn === playerNum && !state.over && mana >= card.mana_cost && !(blockHP && card.type === "HP");
        if (canPlay) {
          div.onclick = () => playCard(i);
          div.style.opacity = "1";
          div.style.cursor = "pointer";
        } else {
          div.style.opacity = "0.5";
          div.style.cursor = "not-allowed";
        }
        // Show reason for block
        if (blockHP && card.type === "HP") {
          div.title = "You are blocked from playing HP cards for " + state[`player${playerNum}`].block_hp + " turn(s)";
        }
        document.getElementById('hand').appendChild(div);
      });

      // Show enemy hand if reveal_hands is active
      if (reveal) {
        const enemyHand = state[`player${playerNum === 1 ? 2 : 1}`].hand;
        document.getElementById('enemy').innerHTML = "<h3>Enemy Hand (Revealed)</h3>";
        enemyHand.forEach(card => {
          const div = document.createElement('div');
          const rarityClass = 'rarity-' + card.rarity.charAt(0) + card.rarity.slice(1).toLowerCase();
          div.className = 'card ' + rarityClass;
          const imgPath = '/' + card.image.replace(/\\/g, '/');
          div.innerHTML = `
            <img src="${imgPath}" alt="${card.name}">
            <div class="card-name">${card.name}</div>
            <div class="card-desc">${card.description}</div>
            <div class="card-meta ${rarityClass}">
              Type: ${card.type} | <span>Rarity: ${card.rarity}</span> | Mana: ${card.mana_cost}
            </div>
          `;
          document.getElementById('enemy').appendChild(div);
        });
      } else {
        document.getElementById('enemy').innerHTML = '';
      }
    }

    setInterval(getGameState, 2000); // Poll for changes
    getGameState(true); // Initial load
  </script>
</body>
</html>