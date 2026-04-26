<?php
	session_start();
	include("connect.php");

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
	    $fullname = $_POST['fullname'] ?? '';
	    $email = $_POST['email'] ?? '';
	    $password = $_POST['password'] ?? '';
	    $confirmpassword = $_POST['confirmpassword'] ?? '';

	    if(!empty($fullname) && !empty($email) && !empty($password) && !empty($confirmpassword))
	    {
		if(!preg_match('/[0-9]/', $password) || !preg_match('/[a-z]/', $password))
		{
		    $error_message = "Password must contain at least one number and one lowercase letter.";
		}elseif($password !== $confirmpassword)
		{
		    $error_message = "Passwords do not match";
		}else
		{
		    $stmt = $con->prepare("SELECT * FROM User WHERE email = ?");
		    $stmt->bind_param("s", $email);
		    $stmt->execute();
		    $result = $stmt->get_result();

		    if($result->num_rows > 0)
		    {
		        $error_message = "Email already exists";
		    }else
		    {
		        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
		        $stmt = $con->prepare("INSERT INTO User (name, email, password) VALUES (?, ?, ?)");
		        $stmt->bind_param("sss", $fullname, $email, $hashed_password);
		        $stmt->execute();
		        if($stmt->affected_rows > 0)
		        {
		            header("Location: login.php");
		            exit();
		        }else
		        {
		            $error_message = "Signup failed";
		        }
		    }
		}
	    }else
	    {
		$error_message = "Please enter valid information";
	    }
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup to EMS</title>
    <link rel="stylesheet" href="sign.css">
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="" method="post">
                    <h2>Signup</h2>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="fullname" required>
                        <label>Fullname</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" required>
                        <label>Password</label>
                        <small class="password-info">
                            Include 1 number and 1 lowercase letter.
                        </small>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="confirmpassword" required>
                        <label>Confirm Password</label>
                    </div>
                    <button type="submit">Sign up</button>
                    <div class="register">
                        <p>Already have an account? <a href="login.php">Login</a> here!</p>
                    </div>
                    <?php if (isset($error_message)) { ?>
                        <div id="error-message" style="color: red; text-align: center; margin-top: 10px;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </section>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
