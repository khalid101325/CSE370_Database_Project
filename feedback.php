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
if (isset($_POST['submit_feedback'])) {
    $booking_id  = 1; // Temporary — replace with real booking_id when booking feature is done
    $rating      = intval($_POST['rating']);
    $review_text = trim($_POST['review_text']);

    if (empty($rating) || $rating < 1 || $rating > 5) {
        $error = "Please select a rating between 1 and 5.";
    } else {
        $stmt = $con->prepare("INSERT INTO Feedback (user_id, booking_id, rating, review_text) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $user_id, $booking_id, $rating, $review_text);
        if ($stmt->execute()) {
            $success = "Feedback submitted successfully! Thank you.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

// Get user's feedback history
$h_stmt = $con->prepare("SELECT feedback_id, rating, review_text, date FROM Feedback WHERE user_id = ? ORDER BY date DESC");
$h_stmt->bind_param("i", $user_id);
$h_stmt->execute();
$h_result = $h_stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feedback</title>
    <link rel="stylesheet" href="feedback.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">Event-MS</div>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="userdetails.php">Profile</a>
            <a href="payment.php">Payment</a>
            <a href="contact.php">Contact</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <h1>Feedback & Reviews</h1>
</header>

<main>
<div class="feedback-wrap">

    <!-- FEEDBACK FORM -->
    <div class="box">
        <h2>Submit Feedback</h2>

        <?php if ($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">

            <label>Rating *</label>
            <div class="star-row">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                <label class="star-label">
                    <input type="radio" name="rating" value="<?php echo $i; ?>"
                           <?php echo (isset($_POST['rating']) && $_POST['rating'] == $i) ? 'checked' : ''; ?>>
                    <span class="star">★</span>
                    <span class="star-num"><?php echo $i; ?></span>
                </label>
                <?php endfor; ?>
            </div>

            <label style="margin-top:14px;">Review / Comment</label>
            <textarea name="review_text" rows="5"
                      placeholder="Tell us about your experience..."><?php echo isset($_POST['review_text']) ? htmlspecialchars($_POST['review_text']) : ''; ?></textarea>

            <button type="submit" name="submit_feedback">Submit Feedback</button>

        </form>
    </div>

    <!-- FEEDBACK HISTORY -->
    <div class="box">
        <h2>My Previous Feedback</h2>

        <?php if ($h_result->num_rows == 0): ?>
            <p class="no-data">No feedback submitted yet.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($f = $h_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $f['feedback_id']; ?></td>
                    <td>
                        <span class="stars">
                            <?php
                                // Show filled stars
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $f['rating'] ? '★' : '☆';
                                }
                            ?>
                        </span>
                        (<?php echo $f['rating']; ?>/5)
                    </td>
                    <td class="review-preview"><?php echo htmlspecialchars($f['review_text'] ?: '—'); ?></td>
                    <td><?php echo date('d M Y', strtotime($f['date'])); ?></td>
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
