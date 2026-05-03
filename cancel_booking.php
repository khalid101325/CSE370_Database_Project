<?php

// ─── BACKEND PART (Server-Side Logic) ───
session_start();
include("connect.php");

// BACKEND SECURITY:
if(!isset($_SESSION['email'])) { 
    header("Location: login.php");
    exit(); 
}

// BACKEND DATA:
if(isset($_GET['id'])) {
    $b_id = $_GET['id'];
    
    // STEP 1: FIND THE LINKED SCHEDULE
    // Before we cancel, we need to know which venue/date (schedule_id) was used.
    $find_sql = "SELECT schedule_id FROM Booking WHERE booking_id = '$b_id'";
    $find_res = $con->query($find_sql);
    $booking_data = $find_res->fetch_assoc();
    $s_id = $booking_data['schedule_id'];

    // STEP 2: UPDATE BOOKING STATUS
    // We change the status to 'Cancelled' instead of deleting the row. This keeps a record.
    $cancel_sql = "UPDATE Booking SET booking_status = 'Cancelled' WHERE booking_id = '$b_id'";
    
    // STEP 3: RELEASE THE VENUE SLOT
    // Now that the booking is cancelled, we make the date/venue "Available" again.
    $release_sql = "UPDATE Event_Schedule SET availability_status = 'Available' WHERE schedule_id = '$s_id'";

    // STEP 4: EXECUTION
    // We run both updates. If both succeed, we move on.
    if($con->query($cancel_sql) && $con->query($release_sql)) {
        
        // BACKEND REDIRECT: 
        // Sends the user back to their history page with a "Cancelled" message in the URL.
        echo "<script>alert('Booking Cancelled'); window.location='my_bookings.php';</script>";
    } 
    else {
        // ERROR HANDLING: If the database fails (e.g., connection lost).
        echo "Error cancelling booking."; 
    }
} else {
    // SECURITY: If someone tries to open this file directly without a Booking ID.
    header("Location: my_bookings.php"); 
}
?>
