<?php
session_start();
include("connect.php");

if(!isset($_SESSION['email'])) { header("Location: login.php"); exit(); }

$booking_id = $_GET['id'] ?? 0;
if($booking_id == 0) { header("Location: my_bookings.php"); exit(); }

if(isset($_POST['save_custom'])) {
    $catering = $_POST['catering'];
    $decor = $_POST['decor'];
    $photo = $_POST['photo'];

    $sql = "INSERT INTO Service_Customization (booking_id, catering_type, decoration_style, photography_service) 
            VALUES ('$booking_id', '$catering', '$decor', '$photo')";
    
    if($con->query($sql)) {
        echo "<script>alert('Services Added!'); window.location='my_bookings.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Services</title>
    <link rel="stylesheet" href="index.css">
    <style>
        header { height: auto; min-height: 100vh; background-attachment: fixed; }
        .htxt { position: relative; margin: 50px auto; max-width: 450px; width: 95%; transform: none; top:0; left:0; }
        .form-box { background: rgba(0,0,0,0.8); padding: 30px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.2); text-align: left; }
        select, button { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; }
        button { background: indianred; color: white; border: none; cursor: pointer; font-weight: bold; }
        label { color: indianred; font-weight: bold; display: block; margin-top: 15px; }
    </style>
</head>
<body>
    <header>
        <nav><div class="logo">Event-MS</div><div class="menu"><a href="my_bookings.php">Back</a></div></nav>
        <section class="htxt">
            <span>Optional Features</span>
            <h1>Customize Booking #<?php echo $booking_id; ?></h1>
            <div class="form-box">
                <form method="POST">
                    <label>Catering Type</label>
                    <select name="catering">
                        <option value="Buffet">Buffet</option>
                        <option value="Kacchi">Traditional Kacchi</option>
                        <option value="Snacks">Light Snacks Only</option>
                    </select>

                    <label>Decoration Style</label>
                    <select name="decor">
                        <option value="Floral">Floral Elegant</option>
                        <option value="Modern">Modern Minimalist</option>
                        <option value="Vintage">Vintage Theme</option>
                    </select>

                    <label>Photography</label>
                    <select name="photo">
                        <option value="Yes">Yes, Please</option>
                        <option value="No">No, Thanks</option>
                    </select>

                    <button type="submit" name="save_custom">Save Services</button>
                </form>
            </div>
        </section>
    </header>
</body>
</html>