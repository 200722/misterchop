<?php
session_start();
if (!isset($_SESSION['availableSlots']) || !isset($_SESSION['chosenDate'])) {
    header('Location: ../html/book.html'); // Redirect if no session data
    exit;
}
$availableSlots = $_SESSION['availableSlots'];
$chosenDate = $_SESSION['chosenDate'];
session_destroy(); // Clear session data after use
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/thankyou.css"> <!-- Reuse the same CSS -->
    <title>Time Slot Unavailable</title>
</head>
<body>
    <div class="thank-you-container">
        <img src="./fotos/DALL·E 2024-01-06 14.56.17 - A simple and friendly SVG icon for a barber shop website, indicating that a booking is not possible and encouraging the client to make another appoint.png" alt="Slot Taken" class="thank-you-icon">
        <h1>Time Slot Unavailable</h1>
        <p>Sorry, the selected time slot is already booked. Please choose another time for your appointment.</p>
        <h2>Available Slots:</h2>
        <ul>
            <?php foreach ($availableSlots as $slot): ?>
                <li><?php echo $slot; ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="../html/book.html" class="button">Choose Another Time</a>
    </div>
</body>
</html>
