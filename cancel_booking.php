<?php
session_start();
include("connect.php");

if(!isset($_SESSION['email'])) { header("Location: login.php"); exit(); }

if(isset($_GET['id'])) {
    $b_id = $_GET['id'];
    
    $find_sql = "SELECT schedule_id FROM Booking WHERE booking_id = '$b_id'";
    $find_res = $con->query($find_sql);
    $booking_data = $find_res->fetch_assoc();
    $s_id = $booking_data['schedule_id'];

    $cancel_sql = "UPDATE Booking SET booking_status = 'Cancelled' WHERE booking_id = '$b_id'";
    
    $release_sql = "UPDATE Event_Schedule SET availability_status = 'Available' WHERE schedule_id = '$s_id'";

    if($con->query($cancel_sql) && $con->query($release_sql)) {
        header("Location: my_bookings.php?msg=Cancelled");
    } else {
        echo "Error cancelling booking.";
    }
} else {
    header("Location: my_bookings.php");
}
?>
