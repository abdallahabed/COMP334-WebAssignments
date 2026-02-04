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
    die("Error fetching trip details: " . $e->getMessage());
}

function displayList($data) {
    $items = explode('|', $data);
    echo "<ul>";
    foreach ($items as $item) {
        echo "<li>" . htmlspecialchars($item) . "</li>";
    }
    echo "</ul>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($trip['trip_name']); ?> | Falasteen Trails</title>
</head>
<body>

<?php include 'header.php'; ?>


<main>
  <div align="center">
    <h2><?php echo htmlspecialchars($trip['trip_name']); ?></h2>
    <p><strong>Destination:</strong> <?php echo htmlspecialchars($trip['destination']); ?></p>
    <p><strong>Duration:</strong> <?php echo $trip['duration_days']; ?> days</p>
    <p><strong>Price:</strong> $<?php echo $trip['price']; ?></p>
    <p><strong>Available Seats:</strong> <?php echo $trip['available_seats']; ?></p>
    <p><strong>Dates:</strong> <?php echo $trip['start_date'] . " to " . $trip['end_date']; ?></p>
    <figure>
      <img src="<?php echo htmlspecialchars($trip['image_url']); ?>" alt="<?php echo htmlspecialchars($trip['trip_name']); ?>" width="400">
      <figcaption><?php echo htmlspecialchars($trip['trip_name']); ?></figcaption>
    </figure>
    <p><?php echo nl2br(htmlspecialchars($trip['description'])); ?></p>
  </div>

  <section>
    <details>
        <summary>Day-by-Day Itinerary</summary>
        <?php displayList($trip['itinerary']); ?>
    </details>

    <details>
        <summary>Included Services</summary>
        <?php displayList($trip['inclusions']); ?>
    </details>

    <details>
        <summary>Not Included</summary>
        <?php displayList($trip['exclusions']); ?>
    </details>

    <details>
        <summary>Requirements</summary>
        <?php displayList($trip['requirements']); ?>
    </details>

    <div align="center">
        <a href="booking.php?id=<?php echo $trip['trip_id']; ?>" target="_blank">
            <button>Book This Trip</button>
        </a>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>


</body>
</html>
