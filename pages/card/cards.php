<?php
session_start();

$action = $_GET['action'] ?? '';
$gameId = $_GET['game'] ?? '';
$playerNum = $_GET['player'] ?? null;
$cardIndex = $_GET['card'] ?? null;

$gameDir = 'games';
if (!file_exists($gameDir)) mkdir($gameDir);

$gamesFile = "$gameDir/$gameId.json";
$p1File = "$gameDir/$gameId.p1";
$p2File = "$gameDir/$gameId.p2";

function getCardPool() {
    $db = new SQLite3(__DIR__ . '/../../db/mka.db');
    $result = $db->query('SELECT * FROM cards');
    $cards = [];
    $rarityWeights = [
        'COMMON' => 60,
        'UNCOMMON' => 20,
        'RARE' => 10,
        'EPIC' => 5,
        'LEGENDARY' => 3,
        'MYTHICAL' => 1
    ];
    // testing rarity weights
    $rarityWeights = [
        'COMMON' => 20,
        'UNCOMMON' => 20,
        'RARE' => 20,
        'EPIC' => 20,
        'LEGENDARY' => 15,
        'MYTHICAL' => 5
    ];


    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $card = [
            'name' => $row['name'],
            'description' => $row['description'],
            'type' => strtoupper($row['type']),
            'rarity' => strtoupper($row['rarity']),
            'mana_cost' => (int)$row['mana_cost'],
            'image' => $row['image'],
            'card_ID' => (int)$row['card_ID'],
        ];
        // Add the card multiple times based on its rarity weight
        $weight = $rarityWeights[$card['rarity']] ?? 1;
        for ($i = 0; $i < $weight; $i++) {
            $cards[] = $card;
        }
    }
    $db->close();
    return $cards;
}

function saveGame($state) {
    global $gamesFile;
    file_put_contents($gamesFile, json_encode($state));
}

function loadGame() {
    global $gamesFile;
    return json_decode(file_get_contents($gamesFile), true);
}

function createGame() {
    $cardPool = getCardPool();
    shuffle($cardPool);
    $deckSize = 20;
    $handSize = 5;
    $deck1 = array_slice($cardPool, 0, $deckSize);
    shuffle($cardPool);
    $deck2 = array_slice($cardPool, 0, $deckSize);

    $game = [
        "turn" => 1,
        "player1" => [
            "hp" => 30,
            "mana" => 10, // Start with 10 mana
            "hand" => array_splice($deck1, 0, $handSize),
            "deck" => $deck1
        ],
        "player2" => [
            "hp" => 30,
            "mana" => 10, // Start with 10 mana
            "hand" => array_splice($deck2, 0, $handSize),
            "deck" => $deck2
        ],
        "over" => false,
        "winner" => null
    ];
    saveGame($game);
}

// Assign session player number if not already set
if (!isset($_SESSION[$gameId])) {
    if (!file_exists($p1File)) {
        file_put_contents($p1File, '');
        $_SESSION[$gameId] = 1;
    } elseif (!file_exists($p2File)) {
        file_put_contents($p2File, '');
        $_SESSION[$gameId] = 2;
    } else {
        http_response_code(403);
        exit("Game full.");
    }
}
$playerNum = $_SESSION[$gameId];

// Handle state fetch
if ($action === 'state') {
    // Wait until both players have joined
    if (!file_exists($p1File) || !file_exists($p2File)) {
        echo json_encode(["waiting" => true, "playerNum" => $playerNum]);
        exit;
    }

    // If game file doesn't exist, initialize it
    if (!file_exists($gamesFile)) {
        createGame();
    }

    $state = loadGame();
    $state['playerNum'] = $playerNum;
    echo json_encode($state);
    exit;
}

// Handle skip turn
if ($action === 'skip') {
    if (!file_exists($gamesFile)) exit;
    $state = loadGame();
    if ($state['over']) exit;

    $player = "player$playerNum";
    $opponent = "player" . ($playerNum == 1 ? 2 : 1);

    if ($state['turn'] != $playerNum) exit;

    // Give 5 mana and end turn
    $state[$player]['mana'] += 5;

    // Turn logic
    $state['turn'] = ($opponent === 'player1') ? 1 : 2;

    saveGame($state);
    exit;
}

// Handle card play
if ($action === 'play') {
    if (!file_exists($gamesFile)) exit;

    $state = loadGame();
    if ($state['over']) exit;

    $player = "player$playerNum";
    $opponent = "player" . ($playerNum == 1 ? 2 : 1);

    if ($state['turn'] != $playerNum) exit;
    if (!isset($state[$player]['hand'][$cardIndex])) exit;

    $card = $state[$player]['hand'][$cardIndex];
    $cardType = strtoupper($card['type']);
    $cardName = $card['name'];

    // Block HP cards if under effect
    if ($cardType === 'HP' && isset($state[$player]['block_hp']) && $state[$player]['block_hp'] > 0) {
        exit;
    }

    // Check mana
    if ($state[$player]['mana'] < $card['mana_cost']) exit;
    $state[$player]['mana'] -= $card['mana_cost'];

    // EFFECTS
    switch ($cardName) {
        case "I see you, you see me":
            // Reveal hands for 1 turn (set for opponent's next turn)
            $state['reveal_hands'] = 2; // 2 means: this turn and next opponent's turn
            break;
        case "Fortresses keep you safe":
        case "My fragile bones cease to desist":
            // Half next attack
            $state[$player]['half_next'] = true;
            break;
        case "Candlelight burning bright ":
            // Burn a random card from opponent's hand
            if (!empty($state[$opponent]['hand'])) {
                $burn = array_rand($state[$opponent]['hand']);
                array_splice($state[$opponent]['hand'], $burn, 1);
            }
            break;
        case "All the way down":
            // Remove opponent's mana
            $state[$opponent]['mana'] = 0;
            break;
        case "But it's a lie, you cannot heal":
            // Block opponent from playing HP cards for 2 turns
            $state[$opponent]['block_hp'] = 2;
            break;
        case "Shatter, Shatter, Shatter!":
            // Extra turn
            $state['extra_turns'][$player] = ($state['extra_turns'][$player] ?? 0) + 1;
            break;
        case "As a memory I beg to keep":
            // Immunity to card stealing
            $state[$player]['immune_steal'] = true;
            break;
        case "Not good enough":
            // Reroll hand
            $handCount = count($state[$player]['hand']);
            $state[$player]['hand'] = [];
            for ($i = 0; $i < $handCount; $i++) {
                if (count($state[$player]['deck']) > 0) {
                    $state[$player]['hand'][] = array_shift($state[$player]['deck']);
                }
            }
            break;
        case "No I will not fight":
            // Skip turn, gain 5 mana
            $state[$player]['mana'] += 5;
            // End turn logic below
            break;
        case "My hands will make you bleed":
        case "I'll hurt you to the beat":
        case "These blades I'm hiding":
            // Deal 5 damage (check for defense)
            $damage = 5;
            if (isset($state[$opponent]['half_next']) && $state[$opponent]['half_next']) {
                $damage = ceil($damage / 2);
                unset($state[$opponent]['half_next']);
            }
            $state[$opponent]['hp'] -= $damage;
            break;
        case "A blade of silver across your chest ":
            // Deal 10 damage (check for defense)
            $damage = 10;
            if (isset($state[$opponent]['half_next']) && $state[$opponent]['half_next']) {
                $damage = ceil($damage / 2);
                unset($state[$opponent]['half_next']);
            }
            $state[$opponent]['hp'] -= $damage;
            break;
        case "I’ll wear the blood, You'll wear the wounds ":
            // Deal 20 damage (check for defense)
            $damage = 20;
            if (isset($state[$opponent]['half_next']) && $state[$opponent]['half_next']) {
                $damage = ceil($damage / 2);
                unset($state[$opponent]['half_next']);
            }
            $state[$opponent]['hp'] -= $damage;
            break;
        case "They want to hurt, I want to heal":
            // Heal 10 HP
            $state[$player]['hp'] += 10;
            break;
    }

    // Remove HP card block if set and decrement
    if (isset($state[$player]['block_hp']) && $state[$player]['block_hp'] > 0) {
        $state[$player]['block_hp']--;
        if ($state[$player]['block_hp'] <= 0) unset($state[$player]['block_hp']);
    }

    // Remove played card
    array_splice($state[$player]['hand'], $cardIndex, 1);

    // Draw a card if deck not empty
    if (count($state[$player]['deck']) > 0) {
        $state[$player]['hand'][] = array_shift($state[$player]['deck']);
    }

    // Give 5 mana for playing a card
    $state[$player]['mana'] += 5;

    // Clamp HP
    $state[$player]['hp'] = max(0, $state[$player]['hp']);
    $state[$opponent]['hp'] = max(0, $state[$opponent]['hp']);

    // Win check
    if ($state[$opponent]['hp'] <= 0) {
        $state['over'] = true;
        $state['winner'] = $playerNum;
    }

    // Turn logic
    $turnChanged = false;
    if (!$state['over']) {
        // Extra turns
        if (isset($state['extra_turns'][$player]) && $state['extra_turns'][$player] > 0) {
            $state['extra_turns'][$player]--;
        } elseif ($cardName === "No I will not fight") {
            $state['turn'] = ($opponent === 'player1') ? 1 : 2;
            $turnChanged = true;
        } elseif (!isset($state['skip']) || $state['skip'] !== $opponent) {
            $state['turn'] = ($opponent === 'player1') ? 1 : 2;
            $turnChanged = true;
        } else {
            unset($state['skip']);
            $turnChanged = true;
        }
    }

    // Only decrement reveal_hands when the turn changes
    if ($turnChanged && isset($state['reveal_hands'])) {
        $state['reveal_hands']--;
        if ($state['reveal_hands'] <= 0) unset($state['reveal_hands']);
    }

    saveGame($state);
    exit;
}
?>
