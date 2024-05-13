<?php
// Include the database connection file
include 'config.php';

// Function to fetch all user names
function getAllUserNames($conn) {
    $sql = "SELECT DISTINCT name FROM individualusers";
    $result = $conn->query($sql);
    $userNames = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $userNames[] = $row['name'];
        }
    }
    return $userNames;
}

// Check if a user name is selected
if (isset($_GET['userName'])) {
    $selectedUserName = $_GET['userName'];

    // Fetch all events for the selected user
    $sql = "SELECT e.ID, i.name, e.EventName, e.rank
            FROM individualusers AS i
            JOIN individualenrollment AS e ON i.ID = e.userID
            WHERE i.name = '$selectedUserName'";
    $result = $conn->query($sql);
} else {
    // If no user name is selected, fetch all events for all users
    $sql = "SELECT e.ID, i.name, e.EventName, e.rank
            FROM individualusers AS i
            JOIN individualenrollment AS e ON i.ID = e.userID";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="Homes.css">
    
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Welcome, Admin</h3>
                <h3>individual Tournaments</h3>
            </div>
            <div class="col text-end">
                <form method="GET" action="">
                    <div class="form-group">
                        <select class="form-select" name="userName" onchange="this.form.submit()">
                            <option value="">Select User</option>
                            <?php
                            // Fetch all user names and display in the dropdown
                            $userNames = getAllUserNames($conn);
                            foreach ($userNames as $userName) {
                                echo "<option value='$userName'";
                                if (isset($_GET['userName']) && $_GET['userName'] == $userName) {
                                    echo " selected";
                                }
                                echo ">$userName</option>";
                            }
                            ?>
                            
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Rank</th>
                            <th>Enrolled Event</th>
                            <th>Action</th>
                            <th>Delete</th> <!-- New column for the delete button -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["rank"] . "</td>";
                                echo "<td>" . $row["EventName"] . "</td>";
                                // Add a button to direct admin to addRank.php with necessary parameters
                                echo "<td><a href='addRank.php?ID=" . $row['ID'] . "&eventName=" . $row['EventName'] . "&rank=" . $row['rank'] . "' class='btn btn-primary'>Add Rank</a></td>";

                                // Add a button to delete user enrollment
                                echo "<td><a href='deleteEnrollment.php?ID=" . $row['ID'] . "&eventName=" . $row['EventName'] . "' class='btn btn-danger'>Delete</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No users enrolled in any event.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <!-- <a href="addEvents.php" class="btn btn-primary">Add a tournament</a> -->
            </div>
        </div>
    </div>
</body>
</html>
<?php
// Include the database connection file
include 'config.php';

// Function to fetch all team names
function getAllTeamNames($conn) {
    $sql = "SELECT DISTINCT teamName FROM teamedusers";
    $result = $conn->query($sql);
    $teamNames = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $teamNames[] = $row['teamName'];
        }
    }
    return $teamNames;
}

// Check if a team name is selected
if (isset($_GET['teamName'])) {
    $selectedTeamName = $_GET['teamName'];

    // Fetch all events for the selected team
    $sql = "SELECT e.ID, t.teamName, e.EventName, e.rank
            FROM teamedusers AS t
            JOIN teamedenrollment AS e ON t.ID = e.teamID
            WHERE t.teamName = '$selectedTeamName'";
    $result = $conn->query($sql);
} else {
    // If no team name is selected, fetch all events for all teams
    $sql = "SELECT e.ID, t.teamName, e.EventName, e.rank
            FROM teamedusers AS t
            JOIN teamedenrollment AS e ON t.ID = e.teamID";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="Homes.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Teamed Tournaments</h3>
            </div>
            <div class="col text-end">
                <form method="GET" action="">
                    <div class="form-group">
                        <select class="form-select" name="teamName" onchange="this.form.submit()">
                            <option value="">Select Team</option>
                            <?php
                            // Fetch all team names and display in the dropdown
                            $teamNames = getAllTeamNames($conn);
                            foreach ($teamNames as $teamName) {
                                echo "<option value='$teamName'";
                                if (isset($_GET['teamName']) && $_GET['teamName'] == $teamName) {
                                    echo " selected";
                                }
                                echo ">$teamName</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Team Name</th>
                            <th>Rank</th>
                            <th>Enrolled Event</th>
                            <th>Action</th>
                            <th>Delete</th> <!-- New column for the delete button -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["teamName"] . "</td>";
                                echo "<td>" . $row["rank"] . "</td>";
                                echo "<td>" . $row["EventName"] . "</td>";
                                // Add a button to direct admin to addRank.php with necessary parameters
                                echo "<td><a href='addTeamRank.php?ID=" . $row['ID'] . "&eventName=" . $row['EventName'] . "&rank=" . $row['rank'] . "' class='btn btn-primary'>Add Rank</a></td>";

                                // Add a button to delete team enrollment
                                echo "<td><a href='deleteTeamEnrollment.php?ID=" . $row['ID'] . "&eventName=" . $row['EventName'] . "' class='btn btn-danger'>Delete</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No teams enrolled in any event.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="addEvents.php" class="btn btn-primary">Add a tournament</a>
            </div>
        </div>
    </div>
</body>
</html>
