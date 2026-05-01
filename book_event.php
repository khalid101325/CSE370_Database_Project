<?php
session_start();
include("connect.php");

// 1. Check login
if(!isset($_SESSION['email'])) { header("Location: login.php"); exit(); }

// 2. Get the Package ID from the URL (the one you clicked "Select" on)
$pkg_id = $_GET['pkg_id'] ?? 0;

// If someone tries to open this page without selecting a package first, send them back
if($pkg_id == 0) { header("Location: packages.php"); exit(); }

// 3. Fetch the details of ONLY the selected package to show as text
$pkg_query = $con->query("SELECT package_name, price FROM Event_Package WHERE package_id = '$pkg_id'");
$selected_package = $pkg_query->fetch_assoc();

// 4. Fetch available dates and venues
$schedules = $con->query("SELECT * FROM Event_Schedule WHERE availability_status='Available'");

// 5. Handle the "Confirm" button
if(isset($_POST['confirm_booking'])) {
    $email = $_SESSION['email'];
    $user = $con->query("SELECT user_id FROM User WHERE email='$email'")->fetch_assoc();
    $user_id = $user['user_id'];
    
    $sch_id = $_POST['sch_id'];
    $date = date('Y-m-d');

    $sql = "INSERT INTO Booking (user_id, package_id, schedule_id, booking_status, booking_date) 
            VALUES ('$user_id', '$pkg_id', '$sch_id', 'Pending', '$date')";
            
    if($con->query($sql)) {
        echo "<script>
                alert('Booking Confirmed for " . $selected_package['package_name'] . "!'); 
                window.location.href='my_bookings.php';
              </script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>EMS - Confirm Booking</title>
    <link rel="stylesheet" href="index.css">
    <style>
        .form-box { 
            background: rgba(0,0,0,0.8); padding: 40px; border-radius: 15px; 
            border: 1px solid rgba(255,255,255,0.2); width: 400px; margin: 0 auto;
            text-align: left; /* Align labels to the left */
        }
        select, button { width: 100%; padding: 12px; margin: 15px 0; border-radius: 5px; font-size: 16px; }
        button { background: indianred; color: white; border: none; cursor: pointer; font-weight: bold; }
        label { color: #ccc; font-weight: bold; display: block; margin-top: 10px; }
        .pkg-display { font-size: 1.5em; color: indianred; margin-bottom: 20px; text-align: center; display: block; }
    </style>
</head>
<body>
    <header>
        <nav><div class="logo">Event-MS</div><div class="menu"><a href="index.php">Home</a> <a href="packages.php">Packages</a></div></nav>
        
        <section class="htxt">
            <span>Confirm Your Selection for</span>
            <h1><?php echo $selected_package['package_name']; ?></h1>
            
            <div class="form-box">
                <form method="POST">
                    <!-- We show the package name but it is NOT a dropdown anymore -->
                    <p style="text-align: center; color: white;">Total Price: <b>BDT <?php echo $selected_package['price']; ?></b></p>

                    <label>Choose Available Date & Venue:</label>
                    <select name="sch_id" required>
                        <option value="">-- Select Date --</option>
                        <?php while($row = $schedules->fetch_assoc()): ?>
                            <option value="<?php echo $row['schedule_id']; ?>">
                                <?php echo $row['event_date']; ?> at <?php echo $row['venue']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <button type="submit" name="confirm_booking">Confirm Booking Now</button>
                    <p style="text-align: center;"><a href="packages.php" style="color: white; font-size: 12px;">Change Package</a></p>
                </form>
            </div>
        </section>
    </header>
</body>
</html>