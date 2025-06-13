<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../../backend/meta-include.php'; ?>
    <link rel="stylesheet" href="/css/cards.css">
    <title>Card Duel</title>
</head>
<body>
    <main-element class="welcome">
        <h1 title>Card Duel</h1>
    </main-element>

    <main-element>
      <div class="card-div">
        <div id="status"></div>
        <div id="hp"></div>
        <div id="hand"></div>
        <div id="enemy"></div>
        <button id="skipBtn" onclick="skipTurn()" style="margin:10px 0;">Skip Turn (+5 Mana)</button>
      </div>
    </main-element>

    <script>
    const gameId = new URLSearchParams(location.search).get('game');
    let playerNum = null;
    let lastStateJSON = null;

    function getGameState(force = false) {
      fetch(`cards.php?action=state&game=${gameId}`)
        .then(res => res.json())
        .then(state => {
          if (state.expired) {
            document.getElementById('status').innerText = state.message || "Game expired.";
            document.getElementById('hand').innerHTML = '';
            document.getElementById('hp').innerHTML = '';
            document.getElementById('enemy').innerHTML = '';
            document.getElementById('skipBtn').style.display = "none";
            return;
          }
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
        document.getElementById('status').innerText = "Waiting for another player to join... Make sure you add a code to the URL! (e.g. ...card-game.php?game=12345), set it to whatever. this is temporary until its actually released.";
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
      const reveal = (state.reveal_hands && state.reveal_hands > 0) || (state[`player${playerNum}`].reveal_enemy_hand && state[`player${playerNum}`].reveal_enemy_hand > 0);

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