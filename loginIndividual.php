<?php
// Include the database connection file
include 'config.php';

// Initialize the user ID variable
$userID = null;

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define variables and initialize with form values
    $email = $_POST["email"];
    $password = $_POST["password"];
    // Prepare a select statement
    $sql = "SELECT ID FROM individualusers WHERE email = ? AND password = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ss", $email, $password);
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store the user ID if login is successful
            $stmt->bind_result($userID);
            if ($stmt->fetch()) {
                // Credentials are correct, redirect to individualHome.php with the user ID
                header("location: individualHome.php?ID=$userID");
                exit();
            } else {
                // Email or password is incorrect
                $error_message = "Invalid email or password.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        // Close statement
        $stmt->close();
    }
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="registerindividual.css">
</head>
<body>
    <div class="login-box">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="user-box">
                <input type="text" name="email" required="">
                <label>Email</label>
            </div>
            <div class="user-box">
                <input type="password" name="password" required="">
                <label>Password</label>
            </div>
            <center>
                <button type="submit" class="btn">LOGIN</button>
                <?php if (isset($error_message)): ?>
                    <p class="text-danger">
                        <?php echo $error_message; ?>
                    </p>
                <?php endif; ?>
            </center>
        </form>
    </div>
</body>
</html>