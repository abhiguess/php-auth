<?php
// Initialize the session
//session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
//if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
//    header("location: welcome.php");
//    exit;
//}

// Include config file
require_once "../config/db.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    session_start();

    // Check if email is empty
    if(empty(trim($_POST["email"])))
    {
        $email_err = "Please enter email.";
        $_SESSION["email_err"] = $email_err;
        header("location: ../login.php");
    }
    else
    {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"])))
    {
        $password_err = "Please enter your password.";
        $_SESSION["password_err"] = $password_err;
        header("location: ../login.php");
    }
    else
    {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($email_err) && empty($password_err))
    {
        // Prepare a select statement
        $sql = "SELECT id, email, password, fname, lname, profile_image FROM users WHERE email = ?";

        if($stmt = $mysqli->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if($stmt->execute())
            {
                // Store result
                $stmt->store_result();

                // Check if email exists, if yes then verify password
                if($stmt->num_rows == 1)
                {
                    // Bind result variables
                    $stmt->bind_result($id, $email, $hashed_password, $fname, $lname, $profile);
                    if($stmt->fetch())
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["fname"] = $fname;
                            $_SESSION["lname"] = $lname;
                            $_SESSION["profile"] = $profile;

                            // Redirect user to welcome page
                            header("location: ../index.php");
                        }
                        else
                        {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                            $_SESSION["password_err"] = $password_err;
                            header("location: ../login.php");
                        }
                    }
                }
                else
                {
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                    $_SESSION["email_err"] = $email_err;
                    header("location: ../login.php");
                }
            }
            else
            {
                $other_err = "Oops! Something went wrong. Please try again later.";
                $_SESSION["other_err"] = $other_err;
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>