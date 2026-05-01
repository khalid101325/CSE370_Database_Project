<?php
session_start();
include("connect.php");
if(!isset($_SESSION['email'])) { header("Location: login.php"); exit(); }
$email = $_SESSION['email'];

// Update the query to JOIN Service_Customization
$sql = "SELECT b.booking_id, p.package_name, s.event_date, b.booking_status, 
               c.catering_type, c.decoration_style, c.photography_service
        FROM Booking b 
        JOIN Event_Package p ON b.package_id = p.package_id
        JOIN Event_Schedule s ON b.schedule_id = s.schedule_id
        JOIN User u ON b.user_id = u.user_id 
        LEFT JOIN Service_Customization c ON b.booking_id = c.booking_id
        WHERE u.email = '$email' ORDER BY b.booking_id DESC";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="index.css">
    <style>
        header { height: auto; min-height: 100vh; background-attachment: fixed; }
        nav { position: relative; z-index: 1000; }
        .htxt { position: relative; margin: 50px auto; max-width: 1100px; width: 95%; transform: none; top:0; left:0; }
        table { width: 100%; border-collapse: collapse; background: rgba(0, 0, 0, 0.7); margin-top: 20px; border-radius: 10px; overflow: hidden; }
        th, td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); color: white; text-align: center;}
        th { background: indianred; }
        .btn-custom { background: #2ecc71; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 12px; }
        .btn-cancel { background: #ff4757; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; font-size: 12px; }
    </style>
</head>
<body>
    <header>
        <nav><div class="logo">Event-MS</div><div class="menu"><a href="index.php">Home</a> <a href="packages.php">Packages</a> <a href="logout.php">Logout</a></div></nav>
        <section class="htxt">
            <h1>My Booking History</h1>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Package</th>
                    <th>Date</th>
                    <th>Customization</th> <!-- NEW COLUMN -->
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['package_name']; ?></td>
                    <td><?php echo $row['event_date']; ?></td>
                    <td>
                        <?php if($row['catering_type']): ?>
                            <!-- Show choices if already done -->
                            <small>Food: <?php echo $row['catering_type']; ?><br>Decor: <?php echo $row['decoration_style']; ?></small>
                        <?php else: ?>
                            <!-- Show link if NOT done yet -->
                            <a href="customize.php?id=<?php echo $row['booking_id']; ?>" class="btn-custom">Add Services</a>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['booking_status']; ?></td>
                    <td>
                        <?php if(strtolower($row['booking_status']) == 'pending'): ?>
                            <a href="cancel_booking.php?id=<?php echo $row['booking_id']; ?>" class="btn-cancel" onclick="return confirm('Cancel?')">Cancel</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </header>
</body>
</html>