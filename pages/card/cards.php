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
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $card = [
            'name' => $row['name'],
            'description' => $row['description'],
            'type' => strtoupper($row['type']),
            'rarity' => $row['rarity'],
            'mana_cost' => (int)$row['mana_cost'],
            'image' => $row['image'],
            'card_ID' => (int)$row['card_ID'],
        ];
        $cards[] = $card;
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
    $deck1 = $cardPool;
    $deck2 = $cardPool;
    shuffle($deck1);
    shuffle($deck2);

    $game = [
        "turn" => 1,
        "player1" => [
            "hp" => 30,
            "hand" => array_splice($deck1, 0, 3),
            "deck" => $deck1
        ],
        "player2" => [
            "hp" => 30,
            "hand" => array_splice($deck2, 0, 3),
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

// Handle card play
if ($action === 'play') {
    if (!file_exists($gamesFile)) exit;

    $state = loadGame();
    if ($state['over']) exit; // Don't allow moves if game is over

    $player = "player$playerNum";
    $opponent = "player" . ($playerNum == 1 ? 2 : 1);

    if ($state['turn'] != $playerNum) exit;
    if (!isset($state[$player]['hand'][$cardIndex])) exit;

    $card = $state[$player]['hand'][$cardIndex];
    $cardType = strtoupper($card['type']);

    switch ($cardType) {
        case 'ATK':
            if ($card['name'] === 'Sacrifice') {
                $state[$player]['hp'] -= 5;
                $state[$opponent]['hp'] -= 10;
            } else {
                $state[$opponent]['hp'] -= 5; // Default attack value, adjust as needed
            }
            break;
        case 'DEF':
            if ($card['name'] === 'Heaven') {
                $state[$player]['half_next'] = true;
            } elseif ($card['name'] === 'Into Nothing') {
                $state[$player]['immune_next'] = true;
            }
            break;
        case 'SKP':
            if ($card['name'] === 'After Dark' || $card['name'] === 'Glow') {
                $state['skip'] = $opponent;
            } elseif ($card['name'] === 'Perpetual') {
                $state['extra_turns'][$player] = 3;
            }
            break;
        case 'HP':
            if ($card['name'] === 'Resurrection') {
                $state[$player]['hp'] += 10;
            }
            break;
    }

    array_splice($state[$player]['hand'], $cardIndex, 1);

    // Draw a card if deck not empty
    if (count($state[$player]['deck']) > 0) {
        $state[$player]['hand'][] = array_shift($state[$player]['deck']);
    }

    // Check for win/lose
    if ($state[$opponent]['hp'] <= 0) {
        $state['over'] = true;
        $state['winner'] = $playerNum;
    }

    // Turn logic
    if (!$state['over']) {
        // Handle extra turns
        if (isset($state['extra_turns'][$player]) && $state['extra_turns'][$player] > 0) {
            $state['extra_turns'][$player]--;
            // Player keeps turn
        } elseif (!isset($state['skip']) || $state['skip'] !== $opponent) {
            $state['turn'] = ($opponent === 'player1') ? 1 : 2;
        } else {
            unset($state['skip']);
        }
    }

    saveGame($state);
    exit;
}
?>
