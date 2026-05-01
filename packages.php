<?php
session_start();
include("connect.php");
$result = $con->query("SELECT * FROM Event_Package");
?>
<!DOCTYPE html>
<html>
<head>
    <title>EMS - Packages</title>
    <link rel="stylesheet" href="index.css">
    <style>
        /* This makes the container wide enough for cards but keeps the 'Hero' centering */
        .htxt {
            max-width: 1200px;
            width: 95%;
            position: absolute;
            top: 55%; /* Slightly lower to clear the nav */
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .pkg-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .pkg-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px); /* Simple glass effect */
            border: 1px solid rgba(255,255,255,0.2);
            padding: 20px;
            width: 280px;
            border-radius: 15px;
            color: white;
        }
        .pkg-card h3 { color: indianred; margin-bottom: 10px; }
        .pkg-card button { 
            background: indianred; color: white; border: none; 
            padding: 10px 20px; cursor: pointer; border-radius: 5px; margin-top: 10px;
        }
        /* Fix to make sure we can scroll if there are many packages */
        header { height: auto; min-height: 100vh; }
        nav { position: relative; z-index: 100; }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Event-MS</div>
            <div class="menu">
                <a href="index.php">Home</a>
                <a href="packages.php" style="color: indianred;">Packages</a>
                <?php if(isset($_SESSION['email'])): ?>
                    <a href="my_bookings.php">My Bookings</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </nav>

        <section class="htxt">
            <span>Choose Your</span>
            <h1>Event Packages</h1>
            
            <div class="pkg-grid">
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="pkg-card">
                    <h3><?php echo $row['package_name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <p><b>BDT <?php echo $row['price']; ?></b></p>
                    <a href="book_event.php?pkg_id=<?php echo $row['package_id']; ?>"><button>Select</button></a>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
    </header>
</body>
</html>