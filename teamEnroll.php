<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $teamID = $_GET['teamID'];
    $eventName = $_GET['EventName'];

    // Insert the enrollment data into teamedenrollment table
    $sql = "INSERT INTO teamedenrollment (teamID, eventName) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $teamID, $eventName);

    if ($stmt->execute()) {
        // Redirect back to teamHome.php
        header("Location: teamHome.php?ID=$teamID");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
