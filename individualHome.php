<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>individualHome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="individualHome.css">
</head>

<body>
    <?php
    // Include the database connection file
    include "config.php";

    // Check if the user ID is set in the GET data
    if (isset($_GET['ID'])) {
        $id = $_GET['ID'];

        // Retrieve user's data from individualusers table
        $userSql = "SELECT * FROM individualusers WHERE ID = $id";
        $userResult = $conn->query($userSql);
        if ($userResult->num_rows > 0) {
            $userData = $userResult->fetch_assoc();
           

            // Retrieve user's enrolled tournaments from individualenrollment table
            $enrollmentSql = "SELECT eventName, rank FROM individualenrollment WHERE userID = $id";
            $enrollmentResult = $conn->query($enrollmentSql);
            if ($enrollmentResult->num_rows > 0) {
                echo "<div class='container'>";
                echo "<h1>Welcome, {$userData['name']}</h1>";
                echo "<h3>Enrolled Tournaments:</h3>";
                echo "<table class='table table-dark table-striped'>";
                echo "<thead><tr><th>Event Name</th><th>Rank</th></tr></thead>";
                echo "<tbody>";
                while ($enrollmentRow = $enrollmentResult->fetch_assoc()) {
                    echo "<tr><td>{$enrollmentRow['eventName']}</td><td>{$enrollmentRow['rank']}</td></tr>";
                }
                echo "</tbody></table>";
                echo "<div class='container'>";
            } else {
                echo "<p>No tournaments enrolled.</p>";
            }
        } else {
            echo "<p>User not found.</p>";
        }
    }
    ?>

    <h3>Available Tournaments</h3>
    <?php
    include "config.php";

    // Function to count the number of enrollments for a user
    function countUserEnrollments($conn, $userID)
    {
        $countSql = "SELECT COUNT(*) AS count FROM individualenrollment WHERE userID = $userID";
        $countResult = $conn->query($countSql);
        if ($countResult) {
            $countRow = $countResult->fetch_assoc();
            return $countRow['count'];
        }
        return 0;
    }

    function countEventEnrollments($conn, $eventName)
    {
        $countSql = "SELECT COUNT(*) AS count FROM individualenrollment WHERE eventName = '$eventName'";
        $countResult = $conn->query($countSql);
        if ($countResult) {
            $countRow = $countResult->fetch_assoc();
            return $countRow['count'];
        }
        return 0;
    }

    // Check if the user ID is set in the GET data
    if (isset($_GET['ID'])) {
        $userID = $_GET['ID'];

        // Check if the user has already enrolled in 5 tournaments
        if (countUserEnrollments($conn, $userID) >= 5) {
            echo "<p class='text-danger'>You have already enrolled in 5 tournaments. You cannot enroll in more.</p>";
        } else {
            // Display available tournaments
            $sql = "SELECT ID, EventName FROM individualtournaments";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table class='table table-dark table-striped table-hover'>";
                echo "<thead><tr><th>Event Name</th><th>Action</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    $eventName = $row['EventName']; // Define $eventName inside the loop
                    echo "<tr>";
                    echo "<td>{$row['EventName']}</td>";
                    echo "<td>";
                    // Check if the event has occurred more than 3 times
                    if (countEventEnrollments($conn, $eventName) >= 3) {
                        echo "<span class='text-danger'>Event already fully enrolled</span>";
                    } else {
                        // Check if the user is already enrolled in this tournament
                        $checkEnrollmentSql = "SELECT * FROM individualenrollment WHERE userID = $userID AND EventName = '$eventName'";
                        $checkResult = $conn->query($checkEnrollmentSql);
                        if ($checkResult->num_rows > 0) {
                            // User is already enrolled, display a message
                            echo "<span class='text-warning'>Already Enrolled</span>";
                        } else {
                            // User is not enrolled, display enroll button
                            echo "<a href='individualEnroll.php?EventName={$row['EventName']}&userID=$userID' class='btn btn-primary'>Enroll</a>";
                        }
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>No tournaments available.</p>";
            }
        }
    } else {
        echo "<p>User ID not provided.</p>";
    }
    ?>


</body>

</html>