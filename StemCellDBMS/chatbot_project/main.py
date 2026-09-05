from flask import Flask, render_template, request, jsonify
from flask_cors import CORS
import re
import long_responses as long  # imports long_responses.py

app = Flask(__name__)
# allow cross-origin requests (useful when the main site is served by Apache/PHP on a different port)
CORS(app)

def message_probability(user_message, recognised_words, single_response=False, required_words=[]):
    message_certainty = 0
    has_required_words = True

    # count recognised words found in the user message
    for word in user_message:
        if word in recognised_words:
            message_certainty += 1

    # avoid division by zero
    if len(recognised_words) == 0:
        percentage = 0.0
    else:
        percentage = float(message_certainty) / float(len(recognised_words))

    for word in required_words:
        if word not in user_message:
            has_required_words = False
            break

    if has_required_words or single_response:
        return int(percentage * 100)
    else:
        return 0

def check_all_messages(message):
    highest_prob_list = {}

    def response(bot_response, list_of_words, single_response=False, required_words=[]):
        nonlocal highest_prob_list
        highest_prob_list[bot_response] = message_probability(message, list_of_words, single_response, required_words)

    # Default responses
    response('Hello!', ['hello', 'hi', 'hey'], single_response=True)
    response('See you!', ['bye', 'goodbye'], single_response=True)
    response('I\'m doing fine, and you?', ['how', 'are', 'you'], required_words=['how'])
    response('You\'re welcome!', ['thank', 'thanks'], single_response=True)
    response('Thank you!', ['i', 'love', 'code', 'palace'], required_words=['code', 'palace'])
    response(long.R_ADVICE, ['give', 'advice'], required_words=['advice'])
    response(long.R_EATING, ['what', 'you', 'eat'], required_words=['you', 'eat'])

    # Stem cell responses - more flexible phrasings
    response(long.R_STEM_DEFINITION, ['what', 'is', 'stem', 'cell', 'cells'], required_words=['stem'])
    response(long.R_STEM_TYPES, ['types', 'stem', 'cells', 'type'], required_words=['stem'])
    response(long.R_STEM_USES, ['uses', 'use', 'applications', 'stem', 'cells'], required_words=['stem'])
    response(long.R_STEM_IMPORTANCE, ['importance', 'why', 'stem', 'cells'], required_words=['stem'])
    # Small extra help responses
    response('Stem cells are special human cells that can become many different cell types.', ['what', 'can', 'stem', 'cells', 'do'], required_words=['stem'])

    # find best match
    best_match = max(highest_prob_list, key=highest_prob_list.get)
    # if confidence is too low, return unknown
    return long.R_UNKNOWN if highest_prob_list[best_match] < 1 else best_match

def get_response(user_input):
    # split input into words
    split_message = re.split(r'\s+|[,;?!.-]\s*', user_input.lower())
    return check_all_messages(split_message)

@app.route("/")
def home():
    return render_template("index.html")


@app.route('/health')
def health():
    # simple health endpoint used by the PHP front-end to check whether Flask is running
    return jsonify({'status': 'ok'})

@app.route("/get")
def get_bot_response():
    user_text = request.args.get('msg', '')
    return get_response(user_text)

if __name__ == "__main__":
    app.run(debug=True)
