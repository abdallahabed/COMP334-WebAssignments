<?php
require_once 'db_config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid trip ID.");
}

$trip_id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM trips WHERE trip_id = ?");
    $stmt->execute([$trip_id]);
    $trip = $stmt->fetch();

    if (!$trip) {
        die("Trip not found.");
    }
} catch (PDOException $e) {
    die("Error fetching trip details.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Trip | Falasteen Trails</title>
</head>

<body>

<?php include 'header.php'; ?>


<main>

<section>
    <h2>Trip Booking</h2>

    <article>
        <h3><?php echo htmlspecialchars($trip['trip_name']); ?></h3>
        <p><strong>Destination:</strong> <?php echo htmlspecialchars($trip['destination']); ?></p>
        <p><strong>Dates:</strong> <?php echo $trip['start_date']; ?> to <?php echo $trip['end_date']; ?></p>
        <p><strong>Duration:</strong> <?php echo $trip['duration_days']; ?> days</p>
        <p><strong>Price per person:</strong> $<?php echo number_format($trip['price'], 2); ?></p>
        <p><strong>Available seats:</strong> <?php echo $trip['available_seats']; ?></p>
    </article>
</section>

<section>
    <h2>Booking Form</h2>

    <form method="POST" action="process-booking.php">

        <!-- Hidden Trip Info -->
        <input type="hidden" name="trip_id" value="<?php echo $trip['trip_id']; ?>">
        <input type="hidden" name="trip_name" value="<?php echo htmlspecialchars($trip['trip_name']); ?>">
        <input type="hidden" name="trip_price" value="<?php echo $trip['price']; ?>">

        <fieldset>
            <legend><strong>Customer Information</strong></legend>

            <label for="customer_name">Full Name:</label><br>
            <input type="text" id="customer_name" name="customer_name" required><br><br>

            <label for="customer_email">Email Address:</label><br>
            <input type="email" id="customer_email" name="customer_email" required><br><br>

            <label for="customer_phone">Phone Number:</label><br>
            <input type="tel" id="customer_phone" name="customer_phone" required>
        </fieldset>

        <br>

        <fieldset>
            <legend><strong>Booking Details</strong></legend>

            <label for="num_travelers">Number of Travelers:</label><br>
            <input type="number" id="num_travelers" name="num_travelers" min="1" required><br><br>

            <label for="special_requests">Special Requests:</label><br>
            <textarea id="special_requests" name="special_requests" rows="4" cols="40"></textarea>
        </fieldset>

        <br>

        <fieldset>
            <legend><strong>Payment Information</strong></legend>

            <label>
                <input type="radio" name="payment_method" value="Visa Card" required>
                Visa Card
            </label><br>

            <label>
                <input type="radio" name="payment_method" value="Master Card">
                Master Card
            </label><br><br>

            <label for="card_number">Card Number (16 digits):</label><br>
            <input type="text" id="card_number" name="card_number" maxlength="16" pattern="[0-9]{16}" required><br><br>

            <label for="cardholder_name">Cardholder Name:</label><br>
            <input type="text" id="cardholder_name" name="cardholder_name" pattern="[A-Za-z ]+" required><br><br>

            <label for="expiry_date">Expiry Date (MM/YYYY):</label><br>
            <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YYYY" pattern="(0[1-9]|1[0-2])\/[0-9]{4}" required>
        </fieldset>

        <br>

        <button type="submit">Confirm Booking</button>

    </form>
</section>

</main>

<hr>

<?php include 'footer.php'; ?>

</body>
</html>
