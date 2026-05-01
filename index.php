<?php
    session_start();
    include("connect.php");

    $logged_in = isset($_SESSION['email']);
    $user = null;

    if($logged_in)
    {
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
    <link rel="stylesheet" href="index.css">
</head>
<body>
<header>
    <nav>
        <div class="logo">Event-MS</div>
        <div class="menu">

            <a href="packages.php">Packages</a>

            <?php if($logged_in): ?>
                <a href="my_bookings.php">My Bookings</a>
                <span class="welcome">
                    Hi, <b><?php echo htmlspecialchars($user['name']); ?></b>
                </span>
                <a href="userdetails.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="sign.php">Signup</a>
            <?php endif; ?>

        </div>
    </nav>

    <section class="htxt">
        <span>Enjoy</span>
        <h1>The Best Event Management</h1>
        <br>

        <a href="packages.php">Book your event now !</a>

    </section>
</header>
</body>
</html>
