<?php

// ─── BACKEND PART (Server-Side Logic) ───
session_start();
include("connect.php"); 

// BACKEND SECURITY: Ensure guests cannot access this page. 
if(!isset($_SESSION['email'])) { 
    header("Location: login.php");
    exit(); 
}

// BACKEND DATA: Get the Booking ID from the URL
$booking_id = $_GET['id'] ?? 0;

// LOGIC: If no ID is provided, the page doesn't know what to customize, so go back.
if($booking_id == 0) { 
    header("Location: my_bookings.php"); 
    exit(); 
}

// BACKEND PROCESSING: Runs when the user clicks the "Save Services" button.
if(isset($_POST['save_custom'])) {
    $catering = $_POST['catering'];
    $decor = $_POST['decor'];
    $photo = $_POST['photo'];

    // SQL QUERY: Inserts choices into the Service_Customization table.
    $sql = "INSERT INTO Service_Customization (booking_id, catering_type, decoration_style, photography_service) 
            VALUES ('$booking_id', '$catering', '$decor', '$photo')";
    
    if($con->query($sql)) {
        // SUCCESS: Show a popup and redirect back to the history page.
        echo "<script>alert('Services Added!'); window.location='my_bookings.php';</script>";
    } else {
        // ERROR HANDLING: If the database insert fails.
        echo "Error: " . $con->error;
    }
}
?>

<!-- ─── FRONTEND PART (Client-Side Design) ─── -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Services</title>
    <link rel="stylesheet" href="index.css">
    
    <style>
        
        body { 
            background-image: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.6)), url('images/index.jpg'); 
            background-size: cover; 
            background-attachment: fixed; 
            font-family: Arial, sans-serif; 
        }

        /* Centering the content section like the "Enjoy" homepage text */
        .htxt { 
            position: relative; 
            margin: 80px auto; 
            max-width: 450px; 
            width: 95%; 
            text-align: center; 
        }

        /* Styling the dark form container */
        .form-box { 
            background: rgba(0,0,0,0.8);
            padding: 30px; 
            border-radius: 15px; /* CHANGE: Roundness of corners */
            border: 1px solid rgba(255,255,255,0.2); 
            text-align: left; 
            color: white; 
        }

        select, button { 
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            border-radius: 5px; 
            font-size: 16px; /* CHANGE: Size of text in fields */
        }

        button { 
            background: #ff0000; 
            color: white; 
            border: none; 
            cursor: pointer; 
            font-weight: bold; 
        }

        button:hover {
            background: #ac0909;
        }

        label { 
            color: #f0c060; 
            display: block; 
            margin-top: 10px; 
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <!-- Navigation bar consistent with the rest of the site -->
        <nav>
            <div class="logo">Event-MS</div>
            <div class="menu">
                <a href="my_bookings.php">Back</a>
            </div>
        </nav>
        <section class="htxt">
            <span>Optional Features</span>
            <!-- HTML/PHP: Displays which booking is being updated -->
            <h1>Customize Booking #<?php echo $booking_id; ?></h1>
            
            <div class="form-box">
                <!-- The data selected here is sent to the BACKEND PHP when submitted -->
                <form method="POST">
                    
                    <!-- CHANGE: Add or remove <option> tags based on the services you offer -->
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
