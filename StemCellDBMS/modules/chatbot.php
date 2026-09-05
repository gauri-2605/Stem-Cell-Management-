<?php
// Simple Stem Cell Chatbot (PHP)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_message = trim($_POST['message'] ?? '');
    $response = '';
    if ($user_message === '') {
        $response = 'Please enter a question about stem cells.';
    } else {
        // Improved keyword-based responses
        $msg = strtolower($user_message);
        if (strpos($msg, 'what is') !== false && (strpos($msg, 'stem cell') !== false || strpos($msg, 'stemcell') !== false)) {
            $response = 'A stem cell is an undifferentiated cell that can divide and develop into different specialized cells. They are used in research and treatments like bone marrow transplants.';
        } elseif (strpos($msg, 'types') !== false && strpos($msg, 'stem') !== false) {
            $response = 'Main types include embryonic stem cells (pluripotent) and adult stem cells (multipotent). Induced pluripotent stem cells (iPSCs) are adult cells reprogrammed to behave like embryonic stem cells.';
        } elseif (strpos($msg, 'uses') !== false || strpos($msg, 'applications') !== false || strpos($msg, 'treatment') !== false) {
            $response = 'Stem cells are used in disease research, drug testing and therapies, such as bone marrow transplants. They offer potential treatments for conditions like Parkinson\'s, diabetes, and spinal cord injuries.';
        } elseif (strpos($msg, 'importance') !== false || strpos($msg, 'why') !== false) {
            $response = 'Stem cells are important because they can repair and replace damaged tissues and help researchers understand disease mechanisms.';
        } elseif (strpos($msg, 'storage') !== false || strpos($msg, 'bank') !== false) {
            $response = 'Stem cells are typically stored in cryogenic conditions in specialised storage facilities to maintain viability for future use.';
        } elseif (strpos($msg, 'donor') !== false || strpos($msg, 'donation') !== false) {
            $response = 'Donors provide stem cells for transplantation or research. Proper donor matching increases the chances of successful treatments.';
        } elseif (strpos($msg, 'research') !== false || strpos($msg, 'study') !== false) {
            $response = 'Researchers use stem cells to study development and disease, and to test potential treatments in the lab.';
        } elseif (strpos($msg, 'hello') !== false || strpos($msg, 'hi') !== false) {
            $response = 'Hello! Ask me about stem cell types, uses, storage, donors, or research.';
        } else {
            $response = 'Sorry, I can answer mainly stem cell related questions. Try: "What is a stem cell?", "Types of stem cells", "Uses of stem cells", or "How are stem cells stored?"';
        }
    }
    echo json_encode(['response' => $response]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stem Cell Chatbot</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; }
        .chat-container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 0 10px #ccc; padding: 20px; }
        .chat-log { height: 250px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-bottom: 15px; background: #fafafa; }
        .chat-msg { margin: 8px 0; }
        .user { color: #333; font-weight: bold; }
        .bot { color: #4CAF50; font-weight: bold; }
        .chat-input { width: 80%; padding: 8px; border-radius: 5px; border: 1px solid #ccc; }
        .chat-btn { padding: 8px 16px; background: #4CAF50; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        .chat-btn:hover { background: #388E3C; }
    </style>
</head>
<body>
<div class="chat-container">
    <h2>Stem Cell Chatbot</h2>
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div id="flaskStatus" style="font-weight:bold;color:#888;">Checking backend...</div>
    </div>
    <div class="chat-log" id="chatLog"></div>
    <form id="chatForm" autocomplete="off">
        <input type="text" id="chatInput" class="chat-input" placeholder="Ask about stem cells..." required />
        <button type="submit" class="chat-btn">Send</button>
        <div>
            <a href="/StemCellDBMS/modules/awareness_info.php" target="_blank" style="text-decoration:none;padding:6px 10px;background:#0066cc;color:#fff;border-radius:4px;font-size:14px;">More info</a>
        </div>
    </form>
</div>
<script>
const chatForm = document.getElementById('chatForm');
const chatInput = document.getElementById('chatInput');
const chatLog = document.getElementById('chatLog');
function appendMessage(sender, text) {
    const msgDiv = document.createElement('div');
    msgDiv.className = 'chat-msg';
    msgDiv.innerHTML = `<span class="${sender}">${sender === 'user' ? 'You' : 'Bot'}:</span> ${text}`;
    chatLog.appendChild(msgDiv);
    chatLog.scrollTop = chatLog.scrollHeight;
}
chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const message = chatInput.value;
    appendMessage('user', message);

    // Call the local proxy endpoint (same-origin). The proxy forwards to Flask and falls back to PHP if needed.
    fetch('/StemCellDBMS/modules/chatbot_proxy.php?msg=' + encodeURIComponent(message))
    .then(res => res.text())
    .then(data => {
        appendMessage('bot', data);
    })
    .catch(() => {
        appendMessage('bot', 'Error: unable to contact bot backend');
    });
    chatInput.value = '';
});

// Poll Flask /health to update status indicator
function checkFlask() {
    fetch('/StemCellDBMS/modules/chatbot_proxy.php?health=1')
    .then(res => res.json())
    .then(json => {
        document.getElementById('flaskStatus').textContent = 'Flask: online';
        document.getElementById('flaskStatus').style.color = '#2e7d32';
    })
    .catch(() => {
        document.getElementById('flaskStatus').textContent = 'Flask: offline';
        document.getElementById('flaskStatus').style.color = '#c62828';
    });
}

// check immediately and then every 5 seconds
checkFlask();
setInterval(checkFlask, 5000);
</script>
</body>
</html>
