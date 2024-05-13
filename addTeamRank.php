<?php
// Include the database connection file
include 'config.php';

$ID = isset($_POST['ID']) ? $_POST['ID'] : ''; // Initialize enrollment ID with empty string if not set
$rows = array(); // Initialize $rows as an empty array

if(isset($_GET['ID'])){
    $ID = $_GET['ID'];
    $sql = "SELECT * FROM teamedenrollment WHERE ID=$ID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $rows = $result->fetch_assoc();
    } else {
        echo "No records found for the given enrollment ID.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $eventName  = $_POST['eventName'];
    $rank = $_POST['rank'];

    $sql = "UPDATE teamedenrollment SET eventName='$eventName', rank='$rank' WHERE ID=$ID";
    if($conn->query($sql) === TRUE){
        header("Location: adminHome.php");
        exit;
    }else{
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rank</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Add Rank</h1>
        <form method="POST">
            <input type="hidden" name="ID" value="<?php echo $ID; ?>">
            <div class="mb-3">
                <label for="eventName" class="form-label">Event Name</label>
                <input type="text" class="form-control" id="eventName" name="eventName" value="<?php echo isset($rows['eventName']) ? $rows['eventName'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="rank" class="form-label">Rank</label>
                <input type="text" class="form-control" id="rank" name="rank" value="<?php echo isset($rows['rank']) ? $rows['rank'] : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Rank</button>
        </form>
    </div>
</body>
</html>
