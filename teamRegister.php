<?php
// Include the database connection file
include 'config.php';

// Define variables and initialize with empty values
$teamName = $email = $password = "";
$teamName_err = $email_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate team name
    if (empty(trim($_POST["teamName"]))) {
        $teamName_err = "Please enter your team name.";
    } else {
        $teamName = trim($_POST["teamName"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        // Prepare a select statement to check if the email already exists
        $sql = "SELECT ID FROM teamedusers WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = trim($_POST["email"]);

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting into database
    if (empty($teamName_err) && empty($email_err) && empty($password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO teamedusers (teamName, email, password) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $param_teamName, $param_email, $param_password);
            $param_teamName = $teamName;
            $param_email = $email;
            $param_password = $password; // Store the password as plain text

            if ($stmt->execute()) {
                // Redirect to login page
                header("location: teamLogin.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
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
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="registerindividual.css">
</head>

<body>
    <div class="login-box">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="theform">
            <div class="user-box">
                <input type="text" name="teamName" required="" id="teamName">
                <label>Team Name</label>
                <span class="help-block"><?php echo $teamName_err; ?></span>
            </div>
            <div class="user-box">
                <input type="text" name="email" required="" id="email">
                <label>Email</label>
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="user-box">
                <input type="password" name="password" required="" id="password">
                <label>Password</label>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <center>
            <a onclick="document.getElementsByTagName('form')[0].submit();">
                Register
                <span></span>
            </a>
            </center>
        </form>
    </div>
    <script src="unitTsting.js"></script>
</body>
</html>
