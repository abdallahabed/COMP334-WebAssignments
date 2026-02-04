<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}

//get data from the form 
$trip_id          = $_POST['trip_id'] ?? '';
$trip_name        = $_POST['trip_name'] ?? '';
$trip_price       = $_POST['trip_price'] ?? '';

$customer_name    = trim($_POST['customer_name'] ?? '');
$customer_email   = trim($_POST['customer_email'] ?? '');
$customer_phone   = trim($_POST['customer_phone'] ?? '');

$num_travelers    = (int)($_POST['num_travelers'] ?? 0);
$special_requests = trim($_POST['special_requests'] ?? '');

$payment_method   = $_POST['payment_method'] ?? '';
$card_number      = $_POST['card_number'] ?? '';
$cardholder_name  = trim($_POST['cardholder_name'] ?? '');
$expiry_date      = $_POST['expiry_date'] ?? '';

// validate the data and make sure it is correct and valid
$errors = [];

if ($trip_id === '' || $trip_price === '') {
    $errors[] = "Trip information is missing.";
}

if ($customer_name === '') {
    $errors[] = "Full name is required.";
}

if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
}

if ($customer_phone === '') {
    $errors[] = "Phone number is required.";
}

if ($num_travelers < 1) {
    $errors[] = "Number of travelers must be at least 1.";
}

if ($payment_method === '') {
    $errors[] = "Payment method is required.";
}

if (!preg_match('/^[0-9]{16}$/', $card_number)) {
    $errors[] = "Card number must be exactly 16 digits.";
}

if (!preg_match('/^[A-Za-z ]+$/', $cardholder_name)) {
    $errors[] = "Cardholder name must contain letters only.";
}

if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{4}$/', $expiry_date)) {
    $errors[] = "Expiry date must be in MM/YYYY format.";
}

if (!empty($errors)) {
    echo "<h2>Booking Error</h2><ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
    exit;
}

//see if the trip still has availabe seats and show them to user 
try {
    $stmt = $pdo->prepare("SELECT available_seats, destination FROM trips WHERE trip_id = ?");
    $stmt->execute([$trip_id]);
    $trip = $stmt->fetch();

    if (!$trip) {
        die("Trip not found.");
    }

    if ($num_travelers > $trip['available_seats']) {
        die("Not enough available seats for this booking.");
    }
} catch (PDOException $e) {
    die("Error checking seat availability.");
}

//find the amount the user has to pay based on how many are going
$total_amount = $trip_price * $num_travelers;
$last_four_digits = substr($card_number, -4);

//save the booking data and add it to the data base
try {
    $insert = $pdo->prepare("
        INSERT INTO bookings 
        (trip_id, customer_name, customer_email, customer_phone, num_travelers, total_amount, payment_method, card_number, special_requests)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->execute([
        $trip_id,
        $customer_name,
        $customer_email,
        $customer_phone,
        $num_travelers,
        $total_amount,
        $payment_method,
        $last_four_digits,
        $special_requests
    ]);

    $booking_id = $pdo->lastInsertId();
} catch (PDOException $e) {
    die("Error processing booking.");
}

//when the user take a seat change the number of avaialbe seats
try {
    $update = $pdo->prepare("
        UPDATE trips 
        SET available_seats = available_seats - ? 
        WHERE trip_id = ?
    ");
    $update->execute([$num_travelers, $trip_id]);
} catch (PDOException $e) {
    die("Error updating available seats.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation | Falasteen Trails</title>
</head>

<body>

<?php include 'header.php'; ?>


<main>
<section>
    <h2>Booking Confirmed ✅</h2>

    <article>
        <p><strong>Booking ID:</strong> <?php echo $booking_id; ?></p>
        <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
        <p><strong>Trip:</strong> <?php echo htmlspecialchars($trip_name); ?></p>
        <p><strong>Destination:</strong> <?php echo htmlspecialchars($trip['destination']); ?></p>
        <p><strong>Number of Travelers:</strong> <?php echo $num_travelers; ?></p>
        <p><strong>Total Amount Paid:</strong> $<?php echo number_format($total_amount, 2); ?></p>
    </article>
</section>
</main>

<?php include 'footer.php'; ?>


</body>
</html>
