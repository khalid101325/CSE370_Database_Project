<?php
	session_start();
	include("connect.php");
	if(!isset($_SESSION['email']))
	{
	    header("Location: login.php");
	    exit();
	}
	$email = $_SESSION['email'];
	$success = "";

	if(isset($_POST['update']))
	{
	    $phone = $_POST['phone'];
	    $address = $_POST['address'];
	    $stmt = $con->prepare("UPDATE User SET phone=?, address=? WHERE email=?");
	    $stmt->bind_param("sss", $phone, $address, $email);
	    if($stmt->execute())
	    {
	        $success = "Profile updated successfully!";
	    }
	}

	$stmt = $con->prepare("SELECT user_id, name, email, phone, address FROM User WHERE email=?");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
    <link rel="stylesheet" href="userdetails.css">
</head>
<body>
<header>
<nav>
    <div class="logo">User</div>
    <div class="menu">
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>
    <h1>User Details</h1>
</header>
<main>
    <div class="profile-box">
        <p><b>ID:</b> <?php echo $user['user_id']; ?></p>
        <p><b>Name:</b> <?php echo $user['name']; ?></p>
        <p><b>Email:</b> <?php echo $user['email']; ?></p>

        <?php if($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            <label>Address:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
            <button type="submit" name="update">Update</button>
        </form>
    </div>
</main>
</body>
</html>
