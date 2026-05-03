<?php
// ══════════════════════════════════════════════════════════════
// ─── BACKEND PART (Server-Side Logic) ───
// ══════════════════════════════════════════════════════════════
session_start();

include("connect.php"); 

// BACKEND SECURITY: Ensure guests cannot access this page.
if(!isset($_SESSION['email'])) { 
    header("Location: login.php");
    exit(); 
}

$email = $_SESSION['email'];

// BACKEND SQL: Multi-table JOIN query to fetch data from linked tables.
$sql = "SELECT b.booking_id, p.package_name, s.event_date, s.venue, b.booking_status, c.catering_type, c.decoration_style
        FROM Booking b JOIN Event_Package p ON b.package_id = p.package_id
        JOIN Event_Schedule s ON b.schedule_id = s.schedule_id
        JOIN User u ON b.user_id = u.user_id 
        LEFT JOIN Service_Customization c ON b.booking_id = c.booking_id
        WHERE u.email = '$email' 
        ORDER BY b.booking_id DESC";

$result = $con->query($sql);
?>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- ─── FRONTEND PART (Client-Side Design) ─── -->
<!-- ══════════════════════════════════════════════════════════════ -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title> 
    <link rel="stylesheet" href="index.css">
    
    <style>

        body { 
            /* DESIGN: Background image with dark overlay. */
            background-image: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.6)), url('images/index.jpg'); 
            background-size: cover; 
            background-position: center; 
            background-attachment: fixed; 
            margin: 0; 
            font-family: Arial, sans-serif; 
            color: white; 
        }

        header { height: auto; min-height: 100vh; }

        nav { 
            position: relative; 
            z-index: 1000; 
            background: rgba(0,0,0,0.3); 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 18px 36px; 
        }

        .logo { font-size: 1.8em; font-weight: bold; color: #f0c060; }

        .menu a { color: white; text-decoration: none; margin-left: 15px; font-size: 14px; }
        .menu a:hover { color: #f0c060; }

        .htxt { 
            position: relative; 
            margin: 40px auto; 
            max-width: 1100px; 
            width: 95%; 
            text-align: center; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            background: rgba(0, 0, 0, 0.7); 
            margin-top: 25px; 
            border-radius: 10px; 
            overflow: hidden; 
        }

        th, td { 
            padding: 15px; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
            text-align: center; 
        }

        th { background: indianred; color: white; text-transform: uppercase; font-size: 13px; }

        .btn-cancel { 
            background: #ff4757; 
            color: white; 
            padding: 5px 10px; 
            text-decoration: none; 
            border-radius: 5px; 
            font-size: 12px; 
        }
        .btn-cancel:hover { background: #ff6b81; }

        .btn-add { 
            background: #2ecc71; 
            color: white; 
            padding: 5px 10px; 
            text-decoration: none; 
            border-radius: 5px; 
            font-size: 12px; 
        }
        .btn-add:hover { background: #27ae60; }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Event-MS</div>
            <div class="menu">
                <a href="index.php">Home</a> 
                <a href="packages.php">Packages</a> 
                <a href="gallery.php">Gallery</a>
                <a href="my_bookings.php" style="color: indianred;">My Bookings</a>
                <a href="payment.php">Payment</a>
                <a href="logout.php">Logout</a>
            </div>
        </nav>
        
        <section class="htxt">
            <h1>My Booking History</h1> 
            
            <table>
                <tr>
                    <th>ID</th>
                    <th>Package</th>
                    <th>Venue</th> 
                    <th>Date</th> 
                    <th>Customization</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <!-- BACKEND/FRONTEND MIX: PHP loops through DB results to create table rows -->
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['package_name']; ?></td>
                    <td><?php echo $row['venue']; ?></td> 
                    <td><?php echo $row['event_date']; ?></td> 
                    
                    <td>
                        <!-- LOGIC: Check if customization exists. If not, show Add Services button. -->
                        <?php if($row['catering_type']): 
                            echo "<small>Food: ".$row['catering_type']."<br>Decor: ".$row['decoration_style']."</small>"; 
                        else: 
                            echo "<a href='customize.php?id=".$row['booking_id']."' class='btn-add'>Add Services</a>"; 
                        endif; ?>
                    </td>

                    <td><?php echo $row['booking_status']; ?></td>

                    <td>
                        <!-- LOGIC: Cancel button only shows if status is exactly 'pending' -->
                        <?php if(strtolower($row['booking_status']) == 'pending'): ?>
                            <a href="cancel_booking.php?id=<?php echo $row['booking_id']; ?>" 
                               class="btn-cancel" 
                               onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                        <?php else: ?> 
                            <span style="color: #888;">---</span> 
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>

                <!-- Optional: Show message if no bookings found -->
                <?php if($result->num_rows == 0): ?>
                    <tr><td colspan="7" style="padding: 20px;">No bookings found. <a href="packages.php" style="color: indianred;">Book an event now!</a></td></tr>
                <?php endif; ?>
            </table>
        </section>
    </header>
</body>
</html>
