<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $userID = $_GET['userID'];
    $EventName = $_GET['EventName'];

    // Insert the enrollment data into individualenrollment table
    $sql = "INSERT INTO individualenrollment (userID, eventName) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userID, $EventName);

    if ($stmt->execute()) {
        // Redirect back to individualHome.php
        header("Location: individualHome.php?ID=$userID");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
