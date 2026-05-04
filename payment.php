<?php
session_start();
include("connect.php");

if(!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$stmt = $con->prepare("SELECT user_id, name FROM User WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];

$success = "";
$error   = "";

if(isset($_POST['submit_payment'])) {
    $booking_id     = intval($_POST['booking_id']);
    $transaction_id = trim($_POST['transaction_id']);
    $payment_method = $_POST['payment_method'];
    $payment_date   = $_POST['payment_date'];
    $amount         = $_POST['amount'];

    if(empty($booking_id) || empty($transaction_id) || empty($payment_method) || empty($payment_date) || empty($amount)) {
        $error = "All fields are required.";
    } elseif($amount <= 0) {
        $error = "Amount must be greater than 0.";
    } else {
        // Make sure this booking belongs to this user
        $chk_booking = $con->prepare("SELECT booking_id FROM Booking WHERE booking_id=? AND user_id=?");
        $chk_booking->bind_param("ii", $booking_id, $user_id);
        $chk_booking->execute();
        $chk_booking->store_result();

        if($chk_booking->num_rows == 0) {
            $error = "Invalid booking selected.";
        } else {
            $check = $con->prepare("SELECT payment_id FROM Payment WHERE transaction_id=?");
            $check->bind_param("s", $transaction_id);
            $check->execute();
            $check->store_result();

            if($check->num_rows > 0) {
                $error = "This Transaction ID already exists. Please check again.";
            } else {
                $ins = $con->prepare("INSERT INTO Payment (booking_id, transaction_id, payment_method, payment_date, amount, payment_status) VALUES (?, ?, ?, ?, ?, 'pending')");
                $ins->bind_param("isssd", $booking_id, $transaction_id, $payment_method, $payment_date, $amount);
                if($ins->execute()) {
                    $success = "Payment submitted successfully! Status: Pending. Admin will verify.";
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }
        }
    }
}

$b_stmt = $con->prepare("
    SELECT b.booking_id, ep.package_name, ep.price, es.venue, es.event_date, b.booking_status
    FROM Booking b
    JOIN Event_Package ep  ON b.package_id  = ep.package_id
    JOIN Event_Schedule es ON b.schedule_id = es.schedule_id
    WHERE b.user_id = ? AND b.booking_status IN ('Pending', 'confirmed')
    ORDER BY b.booking_date DESC
");
$b_stmt->bind_param("i", $user_id);
$b_stmt->execute();
$b_result = $b_stmt->get_result();

$h_stmt = $con->prepare("
    SELECT p.payment_id, p.transaction_id, p.payment_method,
           p.payment_date, p.amount, p.payment_status,
           ep.package_name, b.booking_id
    FROM Payment p
    JOIN Booking b         ON p.booking_id  = b.booking_id
    JOIN Event_Package ep  ON b.package_id  = ep.package_id
    WHERE b.user_id = ?
    ORDER BY p.payment_id DESC
");
$h_stmt->bind_param("i", $user_id);
$h_stmt->execute();
$h_result = $h_stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <link rel="stylesheet" href="payment.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">Event-MS</div>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="userdetails.php">Profile</a>
            <a href="my_bookings.php">My Bookings</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <h1>Payment</h1>
</header>

<main>
<div class="payment-wrap">

    <div class="box">
        <h2>Submit Payment Details</h2>

        <?php if($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($b_result->num_rows == 0): ?>
            <p class="no-data">
                No active bookings found. 
                <a href="packages.php">Book an event first</a>.
            </p>
        <?php else: ?>
        <form method="POST">

            <label>Select Booking *</label>
            <select name="booking_id" id="booking-select" onchange="fillAmount(this)" required>
                <option value="">-- Select your booking --</option>
                <?php while($b = $b_result->fetch_assoc()): ?>
                <option value="<?php echo $b['booking_id']; ?>"
                        data-price="<?php echo $b['price']; ?>">
                    #<?php echo $b['booking_id']; ?> —
                    <?php echo htmlspecialchars($b['package_name']); ?> |
                    <?php echo htmlspecialchars($b['venue']); ?> |
                    <?php echo $b['event_date']; ?> |
                    BDT <?php echo number_format($b['price'], 2); ?>
                </option>
                <?php endwhile; ?>
            </select>

            <label>Transaction ID *</label>
            <input type="text" name="transaction_id"
                   placeholder="e.g. TXN8KJ29201X"
                   value="<?php echo isset($_POST['transaction_id']) ? htmlspecialchars($_POST['transaction_id']) : ''; ?>"
                   required>

            <label>Payment Method *</label>
            <select name="payment_method" required>
                <option value="">-- Select method --</option>
                <option value="bkash" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method']=='bkash') ? 'selected':''; ?>>bKash</option>
                <option value="nagad" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method']=='nagad') ? 'selected':''; ?>>Nagad</option>
                <option value="bank"  <?php echo (isset($_POST['payment_method']) && $_POST['payment_method']=='bank')  ? 'selected':''; ?>>Bank Transfer</option>
                <option value="card"  <?php echo (isset($_POST['payment_method']) && $_POST['payment_method']=='card')  ? 'selected':''; ?>>Card</option>
                <option value="cash"  <?php echo (isset($_POST['payment_method']) && $_POST['payment_method']=='cash')  ? 'selected':''; ?>>Cash</option>
            </select>

            <label>Amount (BDT) *</label>
            <input type="number" name="amount" id="amount-field" step="0.01" min="1"
                   placeholder="Auto-filled when booking selected"
                   value="<?php echo isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : ''; ?>"
                   required>

            <label>Payment Date *</label>
            <input type="date" name="payment_date"
                   value="<?php echo isset($_POST['payment_date']) ? $_POST['payment_date'] : date('Y-m-d'); ?>"
                   required>

            <button type="submit" name="submit_payment">Submit Payment</button>
            <p class="note">* Select your booking — the amount will auto-fill. Enter the Transaction ID after sending money. Admin will verify.</p>

        </form>
        <?php endif; ?>
    </div>

    <div class="box">
        <h2>My Payment History</h2>

        <?php if($h_result->num_rows == 0): ?>
            <p class="no-data">No payment records yet.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Booking ID</th>
                    <th>Package</th>
                    <th>Method</th>
                    <th>Transaction ID</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($p = $h_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $p['payment_id']; ?></td>
                    <td>#<?php echo $p['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($p['package_name']); ?></td>
                    <td><?php echo ucfirst($p['payment_method']); ?></td>
                    <td><?php echo htmlspecialchars($p['transaction_id']); ?></td>
                    <td><?php echo $p['payment_date']; ?></td>
                    <td>BDT <?php echo number_format($p['amount'], 2); ?></td>
                    <td>
                        <span class="status <?php echo $p['payment_status']; ?>">
                            <?php echo ucfirst($p['payment_status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</div>
</main>

<script>
function fillAmount(sel) {
    var opt   = sel.options[sel.selectedIndex];
    var price = opt.getAttribute('data-price');
    document.getElementById('amount-field').value = price ? price : '';
}
</script>

</body>
</html>
