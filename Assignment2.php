<?php
// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=room_booking', 'username', 'password');

// Fetch room usage statistics
$query = "SELECT rooms.room_name, COUNT(bookings.booking_id) AS usage_count 
          FROM bookings 
          INNER JOIN rooms ON bookings.room_id = rooms.room_id 
          WHERE bookings.status = 'Confirmed' 
          GROUP BY bookings.room_id";
$stmt = $pdo->prepare($query);
$stmt->execute();
$roomUsage = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user bookings
$userId = 1; // Replace with logged-in user's ID
$query = "SELECT rooms.room_name, booking_date, time_slot, status 
          FROM bookings 
          INNER JOIN rooms ON bookings.room_id = rooms.room_id 
          WHERE bookings.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $userId]);
$userBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Reporting & Analytics</title>
</head>
<body>
<div class="container my-5">
    <h1>Reporting & Analytics</h1>

    <!-- Room Usage Report -->
    <section>
        <h2>Room Usage Report</h2>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Room Name</th>
                <th>Usage Count</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($roomUsage as $room): ?>
                <tr>
                    <td><?= htmlspecialchars($room['room_name']) ?></td>
                    <td><?= htmlspecialchars($room['usage_count']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- User Bookings -->
    <section class="my-5">
        <h2>My Bookings</h2>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Room Name</th>
                <th>Booking Date</th>
                <th>Time Slot</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($userBookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['room_name']) ?></td>
                    <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                    <td><?= htmlspecialchars($booking['time_slot']) ?></td>
                    <td><?= htmlspecialchars($booking['status']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Room Popularity Chart -->
    <section class="my-5">
        <h2>Room Popularity</h2>
        <canvas id="popularityChart"></canvas>
    </section>
</div>

<script>
    const ctx = document.getElementById('popularityChart').getContext('2d');
    const chartData = {
        labels: <?= json_encode(array_column($roomUsage, 'room_name')) ?>,
        datasets: [{
            label: 'Bookings',
            data: <?= json_encode(array_column($roomUsage, 'usage_count')) ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };
    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
</script>
</body>
</html>