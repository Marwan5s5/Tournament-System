<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="teamHome.css">
</head>

<body>
    <?php
    // Include the database connection file
    include "config.php";

    // Check if the team ID is set in the GET data
    if (isset($_GET['ID'])) {
        $id = $_GET['ID'];

        // Retrieve team's data from teamedusers table
        $teamSql = "SELECT * FROM teamedusers WHERE ID = $id";
        $teamResult = $conn->query($teamSql);
        if ($teamResult->num_rows > 0) {
            $teamData = $teamResult->fetch_assoc();
            

            // Retrieve team's enrolled tournaments from teamedenrollment table
            $enrollmentSql = "SELECT eventName, rank FROM teamedenrollment WHERE teamID = $id";
            $enrollmentResult = $conn->query($enrollmentSql);
            if ($enrollmentResult->num_rows > 0) {
                echo "<div class='container'>";
                echo "<h1>Welcome, {$teamData['teamName']}</h1>";
                echo "<h3>Enrolled Tournaments:</h3>";
                echo "<table class='table table-dark table-striped'>";
                echo "<thead><tr><th>Event Name</th><th>Rank</th></tr></thead>";
                echo "<tbody>";
                while ($enrollmentRow = $enrollmentResult->fetch_assoc()) {
                    echo "<tr><td>{$enrollmentRow['eventName']}</td><td>{$enrollmentRow['rank']}</td></tr>";
                }
                echo "</tbody></table>";
                echo "</div>";
            } else {
                echo "<p>No tournaments enrolled.</p>";
            }
        } else {
            echo "<p>Team not found.</p>";
        }
    }
    ?>

   
    <?php
    include "config.php";

    // Function to count the number of enrollments for a team
    function countTeamEnrollments($conn, $teamID)
    {
        $countSql = "SELECT COUNT(*) AS count FROM teamedenrollment WHERE teamID = $teamID";
        $countResult = $conn->query($countSql);
        if ($countResult) {
            $countRow = $countResult->fetch_assoc();
            return $countRow['count'];
        }
        return 0;
    }

    function countEventEnrollments($conn, $eventName)
    {
        $countSql = "SELECT COUNT(*) AS count FROM teamedenrollment WHERE eventName = '$eventName'";
        $countResult = $conn->query($countSql);
        if ($countResult) {
            $countRow = $countResult->fetch_assoc();
            return $countRow['count'];
        }
        return 0;
    }

    // Check if the team ID is set in the GET data
    if (isset($_GET['ID'])) {
        $teamID = $_GET['ID'];

        // Check if the team has already enrolled in 5 tournaments
        if (countTeamEnrollments($conn, $teamID) >= 5) {
            echo "<p class='text-danger'>Your team has already enrolled in 5 tournaments. You cannot enroll in more.</p>";
        } else {
            // Display available tournaments
            $sql = "SELECT ID, EventName FROM teamedtournaments";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<div class='container mt-4'>";
                echo " <h3>Available Tournaments</h3>";
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
                        // Check if the team is already enrolled in this tournament
                        $checkEnrollmentSql = "SELECT * FROM teamedenrollment WHERE teamID = $teamID AND EventName = '$eventName'";
                        $checkResult = $conn->query($checkEnrollmentSql);
                        if ($checkResult->num_rows > 0) {
                            // Team is already enrolled, display a message
                            echo "<span class='text-warning'>Already Enrolled</span>";
                        } else {
                            // Team is not enrolled, display enroll button
                            echo "<a href='teamEnroll.php?EventName={$row['EventName']}&teamID=$teamID' class='btn btn-primary'>Enroll</a>";
                        }
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo "<p>No tournaments available.</p>";
            }
        }
    } else {
        echo "<p>Team ID not provided.</p>";
    }
    ?>

</body>

</html>
