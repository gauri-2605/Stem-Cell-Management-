<?php
// Simple proxy to forward requests to Flask chatbot (same-origin for the browser)
$flask_base = 'http://127.0.0.1:5000';

// Health check
if (isset($_GET['health'])) {
    $url = $flask_base . '/health';
    $opts = ['http'=>['method'=>'GET','timeout'=>1]];
    $context = stream_context_create($opts);
    $result = @file_get_contents($url, false, $context);
    if ($result === false) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['status'=>'down']);
        exit;
    }
    header('Content-Type: application/json');
    echo $result;
    exit;
}

// Chat request
$msg = trim($_GET['msg'] ?? '');
if ($msg === '') {
    echo 'Please enter a question about stem cells.';
    exit;
}

// Try Flask first
$url = $flask_base . '/get?msg=' . urlencode($msg);
$opts = ['http'=>['method'=>'GET','timeout'=>2]];
$context = stream_context_create($opts);
$result = @file_get_contents($url, false, $context);
if ($result !== false) {
    // Flask returns plain text
    echo $result;
    exit;
}

// Fallback: original PHP logic (basic keyword responses)
$lower = strtolower($msg);
$response = '';
if (strpos($lower, 'what is a stem cell') !== false) {
    $response = 'A stem cell is an undifferentiated cell capable of giving rise to various other cell types.';
} elseif (strpos($lower, 'types of stem cells') !== false) {
    $response = 'Main types: Embryonic, Adult, Induced Pluripotent (iPSC).';
} elseif (strpos($lower, 'uses of stem cells') !== false || strpos($lower, 'applications') !== false) {
    $response = 'Stem cells are used in research, regenerative medicine, and treatment of diseases like leukemia.';
} elseif (strpos($lower, 'storage') !== false) {
    $response = 'Stem cells are stored in cryogenic tanks at very low temperatures to preserve viability.';
} elseif (strpos($lower, 'donor') !== false) {
    $response = 'Donors provide stem cells for transplantation or research. Donor matching is important.';
} elseif (strpos($lower, 'disease') !== false) {
    $response = 'Stem cells are used to treat diseases such as leukemia, lymphoma, and some genetic disorders.';
} elseif (strpos($lower, 'research') !== false) {
    $response = 'Research projects study stem cell behavior, therapies, and new medical applications.';
} else {
    $response = 'Sorry, I can answer only stem cell related questions. Try asking about types, uses, storage, donors, or research.';
}

echo $response;

?>
