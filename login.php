<?php
    session_start();
    include("connect.php");
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        if(!empty($email) && !empty($password))
        {
            /* ── fetch role as well ── */
            $stmt = $con->prepare("SELECT user_id, name, password, role FROM User WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0)
            {
                $row             = $result->fetch_assoc();
                $hashed_password = $row['password'];

                if(password_verify($password, $hashed_password))
                {
                    /* ── set common session vars ── */
                    $_SESSION['user_id']   = $row['user_id'];
                    $_SESSION['user_name'] = $row['name'];
                    $_SESSION['email']     = $email;
                    $_SESSION['role']      = $row['role'];

                    /* ── role-based redirect ── */
                    if($row['role'] === 'admin')
                    {
                        $_SESSION['logged_in'] = true;   // admin.php checks this
                        header("Location: admin.php");
                    }
                    else
                    {
                        header("Location: index.php");
                    }
                    exit();
                }
                else
                {
                    $error_message = "Email and password don't match.";
                }
            }
            else
            {
                $error_message = "No user found with this email.";
            }
        }
        else
        {
            $error_message = "Please enter valid information.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login to EMS</title>
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="" method="POST">
                    <h2>Login</h2>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit">Log in</button>
                    <div class="register">
                        <p>Don't have an account? <a href="sign.php">Signup</a> now!</p>
                    </div>
                    <?php if (isset($error_message)): ?>
                        <p style="color:red; text-align:center; margin-top:10px;">
                            <?php echo htmlspecialchars($error_message); ?>
                        </p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
