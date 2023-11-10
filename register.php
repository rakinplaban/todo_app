<?php
    include "dbconnect.php";
// My database connection code.
    // $servername = "localhost";
    // $username = "root";
    // $password = "";
    // $dbname = "todo";

    // $conn = new mysqli($servername, $username, $password, $dbname);

    // if ($conn->connect_error) {
    //     die("Connection failed: " . $conn->connect_error);
    // }

    // session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve user input from the form
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmation = $_POST['confirmation'];

        // Validate user input (you can add more validation as needed)
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirmation)) {
            // Handle validation errors here
            $error_message = "All fields are required.";
        } elseif ($password !== $confirmation) {
            // Handle password mismatch error
            $error_message = "Password and confirmation do not match.";
        } else {
            // Hash the password for security (you should use a strong hashing library)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL statement using placeholders
            $sql = "INSERT INTO user (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
                if ($stmt->execute()) {
                    // Registration was successful
                    $user_id = $stmt->insert_id;
                    $_SESSION["user_id"] = $user_id;
                    header("Location: index.php");
                    exit();
                } else {
                    // Handle database error
                    $error_message = "Registration failed. Please try again.";
                }
                $stmt->close();
            } else {
                // Handle database error
                $error_message = "Registration failed. Please try again.";
            }
        }
    }



?>



<?php 
    $pageTitle = "Register User";
    include "layout.php" 
?>

    <div class="entry bg-success">
        <div>
            <h2 style="text-align: center; color:aliceblue;">Register</h2>
        </div>

        <div class="bg-light border border-success">
            <div class="formview">
                <?php if (!empty($error_message)) : ?>
                    <div class="alert alert-danger" style="background-color: red; color: white;">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="post">
                    
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input class="form-control" autofocus type="text" name="first_name" placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input class="form-control" autofocus type="text" name="last_name" placeholder="Last Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="email" name="email" placeholder="Email Address">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="confirmation">Confirm Password</label>
                        <input class="form-control" type="password" name="confirmation" placeholder="Confirm Password">
                    </div>
                    <input class="btn btn-success" type="submit" value="Register">
                </form>
                <div style="margin-bottom: 10px;"> </div>
                <div style="margin-left: 10px;">
                    Already have an account? <a href="login.php">Log In here.</a>
                </div>
            </div>
            
        </div>
        
    </div>
    

<?php include "ending.php" ?>