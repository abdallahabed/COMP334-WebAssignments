<?php
require 'db_config.php';

$destination = $start_date = $end_date = $min_price = $max_price = $min_duration = "";
$results = [];

// get destintaions from data base to show it to user
$destinations = $pdo->query("SELECT DISTINCT destination FROM trips ORDER BY destination")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $destination   = trim($_POST['destination'] ?? "");
    $start_date    = $_POST['start_date'] ?? "";
    $end_date      = $_POST['end_date'] ?? "";

//check box for price filter
    $enablePrice   = isset($_POST['enablePrice']);
    $min_price     = $enablePrice ? ($_POST['min_price'] ?? "") : "";
    $max_price     = $enablePrice ? ($_POST['max_price'] ?? "") : "";

    // Duration filter
    $duration_filter = $_POST['duration_filter'] ?? "any";
    $min_duration    = ($duration_filter === "custom") ? ($_POST['min_duration'] ?? "") : "";

    $query = "SELECT * FROM trips WHERE 1=1";
    $params = [];

    if ($destination !== "") {
        $query .= " AND destination = :destination";
        $params[':destination'] = $destination;
    }
    if ($start_date !== "") {
        $query .= " AND start_date >= :start_date";
        $params[':start_date'] = $start_date;
    }
    if ($end_date !== "") {
        $query .= " AND end_date <= :end_date";
        $params[':end_date'] = $end_date;
    }
    if ($min_price !== "") {
        $query .= " AND price >= :min_price";
        $params[':min_price'] = $min_price;
    }
    if ($max_price !== "") {
        $query .= " AND price <= :max_price";
        $params[':max_price'] = $max_price;
    }
    if ($min_duration !== "") {
        $query .= " AND duration_days >= :min_duration";
        $params[':min_duration'] = $min_duration;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Trips | Falasteen Trails</title>
</head>
<body>

<?php include 'header.php'; ?>


<main>
<br>

<div>
    <h2>Search Trips</h2>

    
    <form method="POST">

        <fieldset>
            <legend><b>Basic Filters</b></legend>

            <label>Destination:</label><br>
            <select name="destination">
                <option value="">--Any--</option>
                <?php foreach($destinations as $d): ?>
                    <option value="<?php echo htmlspecialchars($d); ?>"
                        <?php if($destination === $d) echo "selected"; ?>>
                        <?php echo htmlspecialchars($d); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Start Date:</label><br>
            <input type="date" name="start_date" value="<?php echo $start_date; ?>"><br><br>

            <label>End Date:</label><br>
            <input type="date" name="end_date" value="<?php echo $end_date; ?>"><br><br>
        </fieldset>
        <br>

        <fieldset>
            <legend><b>Price Filters (Optional)</b></legend>

            <input type="checkbox" id="enablePrice" name="enablePrice"
                <?php echo isset($enablePrice) && $enablePrice ? "checked" : ""; ?>
                onclick="
                    document.getElementById('min_price').disabled = !this.checked;
                    document.getElementById('max_price').disabled = !this.checked;
                ">
            <label for="enablePrice">Enable Price Filter</label>
            <br><br>

            <label>Min Price:</label><br>
            <input type="number" id="min_price" name="min_price"
                   value="<?php echo $min_price; ?>"
                   <?php echo empty($enablePrice) ? "disabled" : ""; ?>><br><br>

            <label>Max Price:</label><br>
            <input type="number" id="max_price" name="max_price"
                   value="<?php echo $max_price; ?>"
                   <?php echo empty($enablePrice) ? "disabled" : ""; ?>><br><br>
        </fieldset>
        <br>
        <fieldset>
            <legend><b>Duration</b></legend>

            <input type="radio" name="duration_filter" value="any"
                <?php echo ($duration_filter ?? "") !== "custom" ? "checked" : ""; ?>
                onclick="document.getElementById('min_duration').disabled = true;">
            Any<br>

            <input type="radio" name="duration_filter" value="custom"
                <?php echo ($duration_filter ?? "") === "custom" ? "checked" : ""; ?>
                onclick="document.getElementById('min_duration').disabled = false;">
            Custom duration<br><br>

            <label>Min Duration (days):</label><br>
            <input type="number" id="min_duration" name="min_duration"
                   value="<?php echo $min_duration; ?>"
                   <?php echo ($duration_filter ?? "") === "custom" ? "" : "disabled"; ?>>
        </fieldset>
        <br>

        <button type="submit">Search</button>
    </form>
</div>

<br><br>

<section>
    <div>
        <h3>Search Results</h3>

        <?php if ($results): ?>
            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>Package Name</th>
                        <th>Destination</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Start Date</th>
                        <th>Available Seats</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($results as $trip): ?>
                        <tr>
                            <td>
                                <a href="trip-details.php?id=<?php echo $trip['trip_id']; ?>" target="_blank">
                                    <?php echo htmlspecialchars($trip['trip_name']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($trip['destination']); ?></td>
                            <td><?php echo $trip['duration_days']; ?> days</td>
                            <td>$<?php echo number_format($trip['price'], 2); ?></td>
                            <td><?php echo $trip['start_date']; ?></td>
                            <td><?php echo $trip['available_seats']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>No trips match your search criteria.</p>
        <?php endif; ?>
    </div>
</section>

<br><br>

</main>

<?php include 'footer.php'; ?>


</body>
</html>
