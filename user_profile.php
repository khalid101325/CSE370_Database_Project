<?php
	session_start();
	include("connect.php");

	if(!isset($_SESSION['email']))
	{
	    header("Location: login.php");
	    exit();
	}

	$email = $_SESSION['email'];
	$stmt = $con->prepare("SELECT name, email, phone, address FROM User WHERE email = ?");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();

	if($result->num_rows === 0)
	{
	    die("User not found.");
	}
	$user = $result->fetch_assoc();
?>

<header>
    <nav>
        <div class="logo">User Profile</div>
        <div class="menu">
            <a href="index.html">Home</a>
        </div>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </nav>
</header>

<main>
    <div class="profile-box">
        <h2>My Profile</h2>

        <p><b>Name:</b> <?php echo $user['name']; ?></p>
        <p><b>Email:</b> <?php echo $user['email']; ?></p>
        <p><b>Phone:</b> <?php echo $user['phone'] ?: "Not set"; ?></p>
        <p><b>Address:</b> <?php echo $user['address'] ?: "Not set"; ?></p>

        <a href="payment.html"><button>Go to Payment</button></a>
    </div>
</main>

<footer>
    <p>Event Management System</p>
</footer>
