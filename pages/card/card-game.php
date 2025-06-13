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
  </style>
</head>
<body>
  <h1>Card Duel</h1>
  <div id="status"></div>
  <div id="hp"></div>
  <div id="hand"></div>
  <div id="enemy"></div>

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

    function updateUI(state) {
      playerNum = state.playerNum;
      if (state.waiting) {
        document.getElementById('status').innerText = "Waiting for another player to join...";
        document.getElementById('hand').innerHTML = '';
        document.getElementById('hp').innerHTML = '';
        return;
      }

      // Show HP for both players
      document.getElementById('hp').innerText =
        `Your HP: ${state['player'+playerNum].hp} | Enemy HP: ${state['player'+(playerNum===1?2:1)].hp}`;

      if (state.over) {
        document.getElementById('status').innerText =
          state.winner == playerNum ? "You win!" : "You lose!";
        document.getElementById('hand').innerHTML = '';
        return;
      }

      document.getElementById('status').innerText =
        state.turn !== playerNum ? "Waiting for opponent..." : "Your turn!";

      const hand = state[`player${playerNum}`].hand;

      document.getElementById('hand').innerHTML = '';
      hand.forEach((card, i) => {
        const div = document.createElement('div');
        div.className = 'card';
        // Normalize image path for web (replace backslashes with slashes)
        const imgPath = '/' + card.image.replace(/\\/g, '/');
        div.innerHTML = `
          <img src="${imgPath}" alt="${card.name}">
          <div class="card-name">${card.name}</div>
          <div class="card-desc">${card.description}</div>
          <div class="card-meta">
            Type: ${card.type} | Rarity: ${card.rarity} | Mana: ${card.mana_cost}
          </div>
        `;
        if (state.turn === playerNum && !state.over) div.onclick = () => playCard(i);
        document.getElementById('hand').appendChild(div);
      });
    }


    setInterval(getGameState, 2000); // Poll for changes
    getGameState(true); // Initial load
  </script>
</body>
</html>