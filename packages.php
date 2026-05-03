<?php
// ══════════════════════════════════════════════════════════════
// ─── BACKEND PART (Server-Side Logic) ───
// ══════════════════════════════════════════════════════════════
session_start();
// LOGIC: Connects to your database. 
include("connect.php"); 

// SQL QUERY: Fetches data from the 'Event_Package' table.
$result = $con->query("SELECT * FROM Event_Package");
?>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- ─── FRONTEND PART (Client-Side Design) ─── -->
<!-- ══════════════════════════════════════════════════════════════ -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EMS - Packages</title> 
    <link rel="stylesheet" href="index.css"> 
    
    <style>

        body {
            /* DESIGN: Background image with dark overlay. */
            background-image: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.6)), url('images/index.jpg');
            background-size: cover; 
            background-position: center; 
            background-attachment: fixed; /* Keeps background still while scrolling */
            margin: 0; 
            font-family: Arial, sans-serif; 
            color: white;
        }

        /* DESIGN: Navbar styling. */
        nav { 
            position: relative; 
            z-index: 1000; 
            background: rgba(0,0,0,0.3); 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 18px 36px; 
        }

        /* CHANGE: Color of the Website Name/Logo */
        .logo { font-size: 1.8em; font-weight: bold; color: #f0c060; }

        .menu a { color: white; text-decoration: none; margin-left: 15px; font-size: 14px; }
        .menu a:hover { color: #f0c060; }

        /* DESIGN: Centering the content section like the homepage hero text. */
        .htxt { 
            position: relative; 
            margin: 10px auto !important; 
            max-width: 1200px; 
            width: 95%; 
            text-align: center; 
        }

        /* DESIGN: The grid layout. */
        .pkg-grid { 
            display: flex; 
            flex-wrap: wrap; 
            justify-content: center; 
            gap: 20px; 
            margin-top: 30px; 
        }

        /* DESIGN: Individual card style. */
        .pkg-card { 
            background: rgba(255,255,255,0.1); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(255,255,255,0.2); 
            padding: 20px; 
            width: 280px; 
            border-radius: 15px; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* CHANGE: Title color inside the card */
        .pkg-card h3 { color: #f0c060; margin-bottom: 10px; }

        /* DESIGN: Select Button. */
        .pkg-card button { 
            background: transparent !important; 
            color: white !important; 
            border: transparent !important; 
            padding: 10px 20px; 
            cursor: pointer; 
            border-radius: 5px; 
            width: 100%; 
            margin-top: 1px; 
            font-weight: bold;
        }
        
        /* Ensures the background covers the whole page even if it gets long */
        header { height: auto; min-height: 100vh; }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Event-MS</div> 
            <div class="menu">
                <a href="index.php">Home</a>
                <a href="packages.php" style="color: indianred;">Packages</a>
                <a href="gallery.php">Gallery</a>
                
                <!-- BACKEND/FRONTEND MIX: -->
                <?php if(isset($_SESSION['email'])): ?>
                    <a href="my_bookings.php">My Bookings</a>
                    <a href="payment.php">Payment</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </nav>

        <section class="htxt">
            <span>CHOOSE YOUR</span>
            <h1>Event Packages</h1>
            
            <div class="pkg-grid">
                <!-- BACKEND/FRONTEND MIX: 
                     Loops through database records and creates a card for each one. -->
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="pkg-card">
                    <!-- DATA MAPPING: Ensure these match your Database Column names exactly -->
                    <h3><?php echo $row['package_name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <p><b>BDT <?php echo $row['price']; ?></b></p> 
                    
                    <!-- LOGIC: Sends the specific package ID to the booking page.
                         CHANGE: Ensure 'book_event.php' is the correct filename. -->
                    <a href="book_event.php?pkg_id=<?php echo $row['package_id']; ?>">
                        <button type="button">Select</button>
                    </a>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
    </header>
</body>
</html>
