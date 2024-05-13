<?php
// Include the database connection file
include 'config.php';

// Define variables and initialize with empty values
$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // Check if admin table already has a row
        $sql = "SELECT * FROM admin";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $email_err = "Admin information already exists.";
        } else {
            // Prepare an insert statement
            $sql = "INSERT INTO admin (name, email, password) VALUES (?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sss", $param_name, $param_email, $param_password);
                $param_name = $name;
                $param_email = $email;
                $param_password = $password; // Store the password as plain text

                if ($stmt->execute()) {
                    // Redirect to login page or any other page
                    header("location: loginAdmin.php");
                    exit();
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
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
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Register</h2>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" value="<?php echo $name; ?>" class="form-control"
                                    id="name">
                                <span class="text-danger">
                                    <?php echo $name_err; ?>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" name="email" value="<?php echo $email; ?>" class="form-control"
                                    id="email">
                                <span class="text-danger">
                                    <?php echo $email_err; ?>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" value="<?php echo $password; ?>"
                                    class="form-control" id="password">
                                <span class="text-danger">
                                    <?php echo $password_err; ?>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>"
                                    class="form-control" id="confirm_password">
                                <span class="text-danger">
                                    <?php echo $confirm_password_err; ?>
                                </span>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="terms">
                                <label class="form-check-label" for="terms">I accept the Terms and Conditions</label>
                            </div>
                            <button type="submit" class="btn w-100">Create an account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>