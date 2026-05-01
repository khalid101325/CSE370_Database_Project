<?php
session_start();
include("connect.php");

// 1. Check if user is logged in
if(!isset($_SESSION['email'])) { header("Location: login.php"); exit(); }

// 2. Get the Booking ID from the URL
if(isset($_GET['id'])) {
    $b_id = $_GET['id'];

    // 3. First, find the schedule_id linked to this booking
    // We need this to make the venue available again
    $find_sql = "SELECT schedule_id FROM Booking WHERE booking_id = '$b_id'";
    $find_res = $con->query($find_sql);
    $booking_data = $find_res->fetch_assoc();
    $s_id = $booking_data['schedule_id'];

    // 4. Update Booking Status to 'Cancelled'
    $cancel_sql = "UPDATE Booking SET booking_status = 'Cancelled' WHERE booking_id = '$b_id'";
    
    // 5. Update Schedule to 'Available'
    $release_sql = "UPDATE Event_Schedule SET availability_status = 'Available' WHERE schedule_id = '$s_id'";

    if($con->query($cancel_sql) && $con->query($release_sql)) {
        // Success! Go back to history
        header("Location: my_bookings.php?msg=Cancelled");
    } else {
        echo "Error cancelling booking.";
    }
} else {
    header("Location: my_bookings.php");
}
?>