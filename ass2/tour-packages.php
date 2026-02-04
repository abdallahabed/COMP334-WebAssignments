<?php
require_once 'db_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Falasteen Trails | Tour Packages</title>
</head>
<body>

  <?php include 'header.php'; ?>


  <main>
    <div align="center">
      <h2>Tour Packages</h2>
    </div>

    <section>
      <table border="1" align="center">
        <caption>Available Tour Packages</caption>
        <thead>
          <tr>
            <th scope="col">Package Name</th>
            <th scope="col">Duration</th>
            <th scope="col">Price</th>
            <th scope="col">Destinations</th>
            <th scope="col">Available Seats</th>
          </tr>
        </thead>
        <tbody>
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM trips");
            $trips = $stmt->fetchAll();

            if ($trips) {
                foreach ($trips as $trip) {
                    echo "<tr>";
                    echo "<td><a href='trip-details.php?id=" . $trip['trip_id'] . "'>"
                        . htmlspecialchars($trip['trip_name']) . "</a></td>";
                    echo "<td>" . $trip['duration_days'] . " days</td>";
                    echo "<td>$" . $trip['price'] . "</td>";
                    echo "<td>" . htmlspecialchars($trip['destination']) . "</td>";
                    echo "<td>" . $trip['available_seats'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No trips found.</td></tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='5'>Error fetching trips: " . $e->getMessage() . "</td></tr>";
        }
        ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5">🌿 Winter discount: 20% off for bookings before Dec 31, 2025. Student and group discounts available.
              <br>
              <a href="#contact">Contact us for custom packages</a>
            </td>
          </tr>
        </tfoot>
      </table>
    </section>

    <aside>
      <h3>Special Offers</h3>
      <ul>
        <li>🌿 Seasonal discounts available for winter and summer packages</li>
        <li>👥 Group booking benefits: Discounts for groups of 10 or more</li>
        <li>⏰ Early bird offers: Book at least 30 days in advance to get 15% off</li>
        <li>🎓 Student and senior discounts available all year round</li>
      </ul>
    </aside>

  </main>

  <?php include 'footer.php'; ?>


</body>
</html>
