Chatbot (Flask) - README

Overview

This folder contains a simple Flask chatbot backend and a static UI. The project is integrated with the PHP site in the parent project so the PHP page can call the Flask backend when available.

Files
- main.py: Flask app (routes: /, /get, /health)
- templates/index.html: Optional UI served by Flask at http://127.0.0.1:5000/
- requirements.txt: Python deps

How to run
1. (Optional) create and activate a virtualenv
   python -m venv .venv
   .\.venv\Scripts\Activate.ps1
2. Install dependencies
   pip install -r requirements.txt
3. Run Flask
   python main.py
4. Open the UI
   - Flask UI: http://127.0.0.1:5000/
   - PHP-integrated UI: http://localhost/StemCellDBMS/modules/chatbot.php (calls Flask if available)

Notes
- The PHP page will try Flask first and fall back to the original PHP logic if Flask is unreachable.
- A small status indicator on the PHP page shows whether Flask is online.

If you want me to configure Apache to proxy requests to Flask so the browser only talks to Apache (no cross-origin), I can add the `mod_proxy` snippet and change the JS to use `/chatbot-api/get`.
