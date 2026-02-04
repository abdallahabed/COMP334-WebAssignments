<?php
// Include the config file
require_once 'db_config.php';  // make sure this path is correct

// Test query: fetch first 5 trips
try {
    $stmt = $pdo->query("SELECT trip_id, trip_name, destination FROM trips LIMIT 5");
    $trips = $stmt->fetchAll();

    echo "<h3>Database Connection Successful!</h3>";

    if ($trips) {
        echo "<h4>Sample Trips:</h4><ul>";
        foreach ($trips as $trip) {
            echo "<li>#" . $trip['trip_id'] . " - " . htmlspecialchars($trip['trip_name']) . " (" . htmlspecialchars($trip['destination']) . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "No trips found in the database.";
    }

} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}
?>
