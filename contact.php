<?php
session_start();
include("connect.php");

// Check login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Get user_id
$stmt = $con->prepare("SELECT user_id, name FROM User WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];

$success = "";
$error   = "";

// Handle form submit
if (isset($_POST['submit_message'])) {
    $message_type = $_POST['message_type'];
    $subject      = trim($_POST['subject']);
    $message      = trim($_POST['message']);
    $booking_id   = !empty($_POST['booking_id']) ? $_POST['booking_id'] : NULL;

    if (empty($message_type) || empty($message)) {
        $error = "Message type and message are required.";
    } else {
        $stmt = $con->prepare("INSERT INTO User_Communication (user_id, booking_id, message_type, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $user_id, $booking_id, $message_type, $subject, $message);
        if ($stmt->execute()) {
            $success = "Message sent successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

// Get user's past messages
$h_stmt = $con->prepare("SELECT communication_id, message_type, subject, message, date FROM User_Communication WHERE user_id = ? ORDER BY date DESC");
$h_stmt->bind_param("i", $user_id);
$h_stmt->execute();
$h_result = $h_stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact / Inquiry</title>
    <link rel="stylesheet" href="contact.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">Event-MS</div>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="userdetails.php">Profile</a>
            <a href="payment.php">Payment</a>
            <a href="feedback.php">Feedback</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <h1>Contact / Inquiry</h1>
</header>

<main>
<div class="contact-wrap">

    <!-- MESSAGE FORM -->
    <div class="box">
        <h2>Send a Message</h2>

        <?php if ($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">

            <label>Message Type *</label>
            <select name="message_type" required>
                <option value="">-- Select type --</option>
                <option value="inquiry"   <?php echo (isset($_POST['message_type']) && $_POST['message_type']=='inquiry')   ? 'selected' : ''; ?>>Inquiry</option>
                <option value="complaint" <?php echo (isset($_POST['message_type']) && $_POST['message_type']=='complaint') ? 'selected' : ''; ?>>Complaint</option>
                <option value="feedback"  <?php echo (isset($_POST['message_type']) && $_POST['message_type']=='feedback')  ? 'selected' : ''; ?>>Feedback</option>
                <option value="update"    <?php echo (isset($_POST['message_type']) && $_POST['message_type']=='update')    ? 'selected' : ''; ?>>Update Request</option>
            </select>

            <label>Subject</label>
            <input type="text" name="subject"
                   placeholder="e.g. Venue change request"
                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">

            <label>Message *</label>
            <textarea name="message" rows="5" placeholder="Write your message here..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>

            <button type="submit" name="submit_message">Send Message</button>

        </form>
    </div>

    <!-- MESSAGE HISTORY -->
    <div class="box">
        <h2>My Previous Messages</h2>

        <?php if ($h_result->num_rows == 0): ?>
            <p class="no-data">No messages sent yet.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($c = $h_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $c['communication_id']; ?></td>
                    <td>
                        <span class="type <?php echo $c['message_type']; ?>">
                            <?php echo ucfirst($c['message_type']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($c['subject'] ?: '—'); ?></td>
                    <td class="msg-preview"><?php echo htmlspecialchars($c['message']); ?></td>
                    <td><?php echo date('d M Y', strtotime($c['date'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</div>
</main>

</body>
</html>
