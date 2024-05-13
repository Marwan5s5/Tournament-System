<?php
// Include the database connection file
include 'config.php';
if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Error: Unable to connect to the database.";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $eventName = $_POST["eventName"];
    $eventType = $_POST["eventType"];

    // Prepare and execute SQL query to insert event into the respective table based on the type selected
    if ($eventType == "team") {
        // Insert into individualtournaments table
        $sql = "INSERT INTO teamedtournaments (eventName) VALUES (?)";
    } elseif ($eventType == "individual") {
        // Insert into individualenrollment table
        $sql = "INSERT INTO individualtournaments (EventName) VALUES (?)";
    }

    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing SQL statement: " . $conn->error);
    }

    $stmt->bind_param("s", $eventName);
    if (!$stmt->execute()) {
        die("Error executing SQL statement: " . $stmt->error);
    }
// Print out the SQL query for debugging
echo "SQL Query: $sql";

    // Redirect back to admin home page after adding event
    header("Location: adminHome.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Add Event</h2>
                        <form action="addEvents.php" method="post">
                            <div class="mb-3">
                                <label for="eventName" class="form-label">Event Name</label>
                                <input type="text" name="eventName" class="form-control" id="eventName">
                            </div>
                            <div class="mb-3">
                                <label for="eventType" class="form-label">Choose Type</label>
                                <select class="form-select" name="eventType" id="eventType">
                                    <option value="team">Team</option>
                                    <option value="individual">Individual</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Event</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
