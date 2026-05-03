<?php
$con = new mysqli("localhost", "root", "", "EventManagementDB");
$con->set_charset("utf8mb4");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$result = $con->query("
    SELECT g.image_path, g.description, e.package_name
    FROM Gallery g
    JOIN Event_Package e ON g.package_id = e.package_id
    ORDER BY g.gallery_id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
    <link rel="stylesheet" href="/Project/gallery.css">
</head>
<body>
<div class="background-fixed"></div>
<nav>
    <div class="logo">Event-MS</div>
    <div class="menu">
        <a href="/Project/index.php">Home</a>
        <a href="/Project/packages.php">Packages</a>
        <a href="/Project/my_bookings.php">My Bookings</a>
    </div>
</nav>
<div class="htxt">
    <h1>Gallery</h1>
    <div class="pkg-grid">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="pkg-card">
                <img src="/Project/<?php echo htmlspecialchars($row['image_path']); ?>" class="gallery-img">
                <h3><?php echo htmlspecialchars($row['package_name']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>