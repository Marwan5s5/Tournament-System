<?php
// Include the database connection file
include 'config.php';

// Check if the ID is set in the GET data
if (isset($_GET['ID'])) {
    $id = $_GET['ID'];

    // Delete the enrollment with the specified ID
    $sql = "DELETE FROM teamedenrollment WHERE ID = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to adminHome.php after successful deletion
        header("Location: adminHome.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "ID parameter is missing.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Enrollment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Confirmation</h1>
        <?php
        // Check if the required parameters are set
        if (isset($_GET['ID']) && isset($_GET['eventName'])) {
            $ID = $_GET['ID'];
            $eventName = $_GET['eventName'];
            echo "<p>Are you sure you want to unenroll this team in the event: <strong>$eventName</strong>?</p>";
            echo "<p><a href='deleteTeamEnrollment.php?ID=$ID' class='btn btn-danger'>Yes, Unenroll</a></p>";
            echo "<p><a href='adminHome.php' class='btn btn-primary'>No, Go Back</a></p>";
        } else {
            echo "<p>Error: Required parameters are missing.</p>";
        }
        ?>
    </div>

    <!-- JavaScript to display the confirmation message -->
    <script>
        function confirmUnenroll() {
            return confirm('Are you sure you want to unenroll this team in this event?');
        }
    </script>
</body>
</html>
