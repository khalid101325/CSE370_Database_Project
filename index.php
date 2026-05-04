<?php
session_start();
include("connect.php");
$logged_in = isset($_SESSION['email']);
if ($logged_in) {
    $email = $_SESSION['email'];
    $stmt = $con->prepare("SELECT name FROM User WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EMS</title>
    <style>

* { padding:0; margin:0; box-sizing:border-box; font-family:Arial, sans-serif; }

body { background:#0d0a08; color:#e8d5b0; }

.bg-section {
    background-image: url('index.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
}


header {
    background: transparent;
    position: absolute;
    top: 0; left: 0; right: 0;
    z-index: 10;
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 36px;
}

.logo {
    font-size: 1.8em;
    font-weight: bold;
    letter-spacing: 2px;
    color: #f0c060;
    text-shadow: 0 2px 8px rgba(0,0,0,0.7);
}

.menu {
    display: flex;
    align-items: center;
    gap: 5px;
}

.menu a {
    color: #e8d5b0;
    text-decoration: none;
    padding: 7px 16px;
    border-radius: 5px;
    font-size: 14px;
    transition: background 0.2s, color 0.2s;
    border: 1px solid transparent;
}

.menu a:hover {
    background: rgba(240, 192, 96, 0.15);
    color: #f0c060;
    border-color: rgba(240,192,96,0.3);
}

.welcome { color:#c9a86c; font-size:14px; margin-right:6px; }
.welcome b { color:#f0c060; }

.hero-section {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.hero-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(5, 2, 0, 0.60) 0%,
        rgba(10, 4, 0, 0.50) 50%,
        rgba(5, 2, 0, 0.75) 100%
    );
}

.htxt {
    text-align: center;
    padding: 140px 20px 100px;
    position: relative;
    z-index: 1;
}

.htxt span {
    font-size: 13px;
    color: #f0c060;
    letter-spacing: 5px;
    text-transform: uppercase;
    font-weight: normal;
}

.htxt h1 {
    font-size: 3.2em;
    margin: 16px 0 28px;
    color: #fff;
    font-weight: bold;
    text-shadow: 0 3px 16px rgba(0,0,0,0.8);
    line-height: 1.2;
}

.htxt a {
    display: inline-block;
    background: #c0392b;
    color: #fff;
    text-decoration: none;
    padding: 15px 40px;
    border-radius: 5px;
    font-size: 17px;
    font-weight: bold;
    letter-spacing: 0.5px;
    border: 1px solid rgba(255,255,255,0.15);
    transition: background 0.2s;
}

.htxt a:hover { background: #a93226; }

.section-title {
    text-align: center;
    font-size: 1.7em;
    margin-bottom: 8px;
    color: #f0c060;
    text-shadow: 0 2px 10px rgba(0,0,0,0.8);
    position: relative;
    z-index: 1;
}

.section-sub {
    text-align: center;
    font-size: 14px;
    margin-bottom: 35px;
    color: #c9a86c;
    position: relative;
    z-index: 1;
    text-shadow: 0 1px 6px rgba(0,0,0,0.7);
}

.contact-section {
    padding: 70px 30px;
}

.contact-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(5, 2, 0, 0.72);
}

.contact-row {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.contact-card {
    background: rgba(240, 192, 96, 0.07);
    border: 1px solid rgba(240, 192, 96, 0.25);
    border-radius: 10px;
    padding: 28px 20px;
    text-align: center;
    width: 190px;
    color: #e8d5b0;
    transition: background 0.2s, border-color 0.2s;
}

.contact-card:hover {
    background: rgba(240, 192, 96, 0.13);
    border-color: rgba(240,192,96,0.45);
}

.cicon { font-size:32px; margin-bottom:12px; }

.contact-card h4 {
    font-size: 15px;
    margin-bottom: 8px;
    color: #f0c060;
    font-weight: bold;
}

.contact-card p {
    font-size: 13px;
    color: #b8a080;
    margin-bottom: 14px;
    line-height: 1.6;
}

.contact-card a {
    display: inline-block;
    background: #c0392b;
    color: white;
    text-decoration: none;
    padding: 7px 16px;
    border-radius: 4px;
    font-size: 12px;
    transition: background 0.2s;
}

.contact-card a:hover { background: #a93226; }

.feedback-section {
    padding: 70px 30px;
}

.feedback-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(3, 1, 0, 0.78);
}

.feedback-row {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.review-card {
    background: rgba(240, 192, 96, 0.07);
    border: 1px solid rgba(240, 192, 96, 0.22);
    border-radius: 10px;
    padding: 26px 22px;
    width: 260px;
    text-align: center;
    color: #e8d5b0;
    transition: background 0.2s, border-color 0.2s;
}

.review-card:hover {
    background: rgba(240, 192, 96, 0.12);
    border-color: rgba(240,192,96,0.40);
}

.review-stars {
    font-size: 22px;
    color: #f0c060;
    margin-bottom: 12px;
    letter-spacing: 2px;
}

.review-card p {
    font-size: 13px;
    color: #c9a86c;
    line-height: 1.7;
    font-style: italic;
    margin-bottom: 14px;
}

.review-card span {
    font-size: 12px;
    color: #8a7055;
}

.feedback-btn {
    display: inline-block;
    background: #c0392b;
    color: white;
    text-decoration: none;
    padding: 14px 38px;
    border-radius: 5px;
    font-size: 15px;
    font-weight: bold;
    margin-top: 32px;
    position: relative;
    z-index: 1;
    transition: background 0.2s;
}

.feedback-btn:hover { background: #a93226; }

.footer {
    background: #080503;
    color: #5a4530;
    text-align: center;
    padding: 22px;
    font-size: 13px;
    border-top: 1px solid rgba(240,192,96,0.12);
}

.chat-bubble {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 56px;
    height: 56px;
    background: #8b1a10;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    cursor: pointer;
    z-index: 1000;
    box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    user-select: none;
    border: 1px solid rgba(240,192,96,0.3);
}
.chat-bubble:hover { background:#6e1409; }

.chat-dot {
    position: absolute;
    top: 6px; right: 6px;
    width: 12px; height: 12px;
    background: #f0c060;
    border-radius: 50%;
    border: 2px solid #0d0a08;
}

.chat-window {
    position: fixed;
    bottom: 94px;
    right: 28px;
    width: 310px;
    background: #1a1008;
    border-radius: 12px;
    box-shadow: 0 8px 36px rgba(0,0,0,0.7);
    border: 1px solid rgba(240,192,96,0.2);
    z-index: 1000;
    display: none;
    flex-direction: column;
    overflow: hidden;
}
.chat-window.open { display:flex; }

.chat-header {
    background: #0d0704;
    color: #f0c060;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(240,192,96,0.15);
}
.chat-header-left { display:flex; align-items:center; gap:10px; }
.chat-avatar {
    width: 36px; height: 36px;
    background: #8b1a10;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: bold;
    color: #f0c060;
    border: 1px solid rgba(240,192,96,0.3);
}
.chat-name   { font-size:14px; font-weight:bold; color:#f0c060; }
.chat-status { font-size:11px; color:#7dbf7d; }
.chat-close  { background:none; border:none; color:#8a7055; font-size:16px; cursor:pointer; }
.chat-close:hover { color:#f0c060; }

.chat-messages {
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #120c05;
    max-height: 200px;
    overflow-y: auto;
}

.msg {
    padding: 9px 13px;
    border-radius: 10px;
    font-size: 13px;
    max-width: 85%;
    line-height: 1.5;
    word-break: break-word;
}
.msg.bot  { background:#2a1a08; color:#e8d5b0; align-self:flex-start; border:1px solid rgba(240,192,96,0.15); border-bottom-left-radius:2px; }
.msg.user { background:#8b1a10; color:#fff; align-self:flex-end; border-bottom-right-radius:2px; }

.chat-questions {
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    background: #1a1008;
    border-top: 1px solid rgba(240,192,96,0.12);
    max-height: 180px;
    overflow-y: auto;
}
.chat-questions button {
    background: #2a1a08;
    border: 1px solid rgba(240,192,96,0.18);
    border-radius: 6px;
    padding: 7px 10px;
    font-size: 12px;
    text-align: left;
    cursor: pointer;
    color: #c9a86c;
    width: 100%;
    transition: background 0.15s;
}
.chat-questions button:hover { background:#3a2510; color:#f0c060; }

.chat-input-row { display:flex; border-top:1px solid rgba(240,192,96,0.12); }
.chat-input-row input {
    flex: 1;
    border: none;
    padding: 10px 12px;
    font-size: 13px;
    outline: none;
    font-family: Arial;
    background: #120c05;
    color: #e8d5b0;
}
.chat-input-row input::placeholder { color:#5a4530; }
.chat-input-row button {
    background: #8b1a10;
    color: #f0c060;
    border: none;
    padding: 10px 14px;
    font-size: 17px;
    cursor: pointer;
}
.chat-input-row button:hover { background:#6e1409; }

@media (max-width:600px) {
    .htxt h1 { font-size:1.9em; }
    .contact-row, .feedback-row { flex-direction:column; align-items:center; }
    .contact-card { width:100%; max-width:340px; }
    .review-card  { width:100%; max-width:340px; }
    .chat-window  { width:92vw; right:4vw; }
    nav { flex-wrap:wrap; gap:8px; padding:14px 18px; }
}
</style>
</head>
<body>

<header>
    <nav>
        <div class="logo">
            Event-MS
        </div>
        <div class="menu">
            
            <?php if($logged_in): ?>
                <span class="welcome">Hi, <b><?php echo htmlspecialchars($user['name']); ?></b></span> 
            <?php endif; ?>
            <a href="packages.php">Packages</a>
            <?php if($logged_in): ?>
                <a href="my_bookings.php">My Bookings</a>
            <?php endif; ?>
            <a href="gallery.php">Gallery</a>
            <?php if(!$logged_in): ?>
                <a href="login.php">Login</a>
                <a href="sign.php">Signup</a>
            <?php endif; ?>
            <?php if($logged_in): ?>
                <a href="payment.php">Payment</a>
                <a href="contact.php">Inquiry</a>
                <a href="userdetails.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<div class="bg-section hero-section">
    <section class="htxt">
        <span>Enjoy</span>
        <h1>The Best Event Management</h1>
        <br>
        <?php if ($logged_in): ?>
            <a href="packages.php">Book your event now!</a>
        <?php else: ?>
            <a href="login.php">Book your event now!</a>
        <?php endif; ?>
    </section>
</div>

<div class="bg-section contact-section">
    <h2 class="section-title">Contact Us</h2>
    <p class="section-sub">Reach us directly through any of these channels</p>
    <div class="contact-row">
        <div class="contact-card">
            <div class="cicon">📧</div>
            <h4>Email</h4>
            <p>khalid101325@gmail.com</p>
            <a href="mailto:khalid101325@gmail.com">Send Email</a>
        </div>
        <div class="contact-card">
            <div class="cicon">📞</div>
            <h4>Phone</h4>
            <p>+880 1609295132</p>
            <a href="tel:+8801609295132">Call Now</a>
        </div>
        <div class="contact-card">
            <div class="cicon">📍</div>
            <h4>Office</h4>
            <p>Dhaka, Bangladesh</p>
            <a href="#">Get Directions</a>
        </div>
        <div class="contact-card">
            <div class="cicon">🕐</div>
            <h4>Working Hours</h4>
            <p>Sat – Thu: 9am – 6pm</p>
            <a href="contact.php">Send Inquiry</a>
        </div>
    </div>
</div>

<div class="bg-section feedback-section">
    <h2 class="section-title">What Our Users Say</h2>
    <p class="section-sub">Share your experience with us</p>
    <div class="feedback-row">
        <div class="review-card">
            <div class="review-stars">★★★★★</div>
            <p>"Amazing service! The event was perfectly organized."</p>
            <span>— Rahim K.</span>
        </div>
        <div class="review-card">
            <div class="review-stars">★★★★☆</div>
            <p>"Very professional team. Highly recommend for corporate events."</p>
            <span>— Sadia A.</span>
        </div>
        <div class="review-card">
            <div class="review-stars">★★★★★</div>
            <p>"Our wedding was a dream come true. Thank you EventManage!"</p>
            <span>— Mahbub H.</span>
        </div>
    </div>
    <div style="text-align:center; position:relative; z-index:1;">
        <?php if ($logged_in): ?>
            <a href="feedback.php" class="feedback-btn">Leave Your Feedback ★</a>
        <?php else: ?>
            <a href="login.php" class="feedback-btn">Login to Leave Feedback ★</a>
        <?php endif; ?>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2025 Event-MS &mdash; University Project</p>
</footer>

<div class="chat-bubble" id="chatBubble" onclick="toggleChat()">
    💬
    <span class="chat-dot" id="chatDot"></span>
</div>

<div class="chat-window" id="chatWindow">
    <div class="chat-header">
        <div class="chat-header-left">
            <div class="chat-avatar">EM</div>
            <div>
                <div class="chat-name">EventManage Support</div>
                <div class="chat-status">● Online</div>
            </div>
        </div>
        <button class="chat-close" onclick="toggleChat()">✕</button>
    </div>

    <div class="chat-messages" id="chatMessages">
        <div class="msg bot">👋 Hi <?php echo $logged_in ? htmlspecialchars($user['name']) : 'there'; ?>! How can I help you?</div>
        <div class="msg bot">Choose a question below 👇</div>
    </div>

    <div class="chat-questions" id="chatQuestions">
        <button onclick="askQuestion('how_book')">📅 How do I book an event?</button>
        <button onclick="askQuestion('how_pay')">💳 How do I make a payment?</button>
        <button onclick="askQuestion('payment_status')">🔍 Check my payment status</button>
        <button onclick="askQuestion('contact_team')">📞 Talk to the team</button>
        <button onclick="askQuestion('working_hours')">🕐 Working hours</button>
        <button onclick="askQuestion('cancel')">❌ How to cancel a booking?</button>
    </div>

    <div class="chat-input-row">
        <input type="text" id="chatInput" placeholder="Type a message..." onkeydown="if(event.key==='Enter') sendChat()">
        <button onclick="sendChat()">➤</button>
    </div>
</div>

<script>
var chatOpen = false;

function toggleChat() {
    chatOpen = !chatOpen;
    var win = document.getElementById('chatWindow');
    if (chatOpen) {
        win.classList.add('open');
        document.getElementById('chatDot').style.display = 'none';
    } else {
        win.classList.remove('open');
    }
}

var answers = {
    how_book:       "To book an event, login to your account and contact our team via the Inquiry page. We will assign a package and schedule for you! 📅",
    how_pay:        "Go to the Payment page from the menu. Enter your Transaction ID, choose your payment method (bKash/Nagad/Bank/Card/Cash) and submit. Admin will confirm it. 💳",
    payment_status: "Your payment status is shown in the Payment History table on the Payment page. It will show Pending, Completed, or Failed. 🔍",
    contact_team:   "You can reach us at:<br>📧 info@eventms.com<br>📞 +880 1700-000000<br>Or use the Inquiry page to send us a message directly.",
    working_hours:  "We are available Saturday to Thursday, 9am to 6pm. For urgent matters, email us anytime! 🕐",
    cancel:         "To cancel a booking, go to the Inquiry page and send a message with type Update Request. Our team will process it within 24 hours. ❌"
};

var qLabels = {
    how_book:       "How do I book an event?",
    how_pay:        "How do I make a payment?",
    payment_status: "Check my payment status",
    contact_team:   "Talk to the team",
    working_hours:  "Working hours",
    cancel:         "How to cancel a booking?"
};

function askQuestion(key) {
    addMessage(qLabels[key], 'user');
    document.getElementById('chatQuestions').style.display = 'none';
    setTimeout(function() {
        addMessage(answers[key], 'bot');
        setTimeout(function() {
            addMessage('Anything else I can help with?', 'bot');
            document.getElementById('chatQuestions').style.display = 'flex';
        }, 600);
    }, 400);
}

function sendChat() {
    var input = document.getElementById('chatInput');
    var text  = input.value.trim();
    if (!text) return;
    addMessage(text, 'user');
    input.value = '';
    setTimeout(function() {
        var t = text.toLowerCase();
        if      (t.indexOf('pay')     > -1) addMessage(answers.how_pay, 'bot');
        else if (t.indexOf('book')    > -1) addMessage(answers.how_book, 'bot');
        else if (t.indexOf('cancel')  > -1) addMessage(answers.cancel, 'bot');
        else if (t.indexOf('hour')    > -1 || t.indexOf('time') > -1) addMessage(answers.working_hours, 'bot');
        else if (t.indexOf('contact') > -1 || t.indexOf('phone') > -1 || t.indexOf('email') > -1) addMessage(answers.contact_team, 'bot');
        else addMessage("Thanks for your message! For detailed help please use our Inquiry page or email info@eventms.com 😊", 'bot');
    }, 400);
}

function addMessage(text, type) {
    var box = document.getElementById('chatMessages');
    var div = document.createElement('div');
    div.className = 'msg ' + type;
    div.innerHTML = text;
    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
}
</script>

</body>
</html>
