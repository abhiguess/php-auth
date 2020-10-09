<?php

// Include config file
require_once "../config/db.php";

// Define variables and initialize with empty values
$fname = $lname = $email = "";
$fname_err = $lname_err = $email_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    session_start();
    // Validate new password
    if(empty(trim($_POST["fname"])))
    {
        $fname_err = "Please enter the your first name.";
    }
    else
    {
        $fname = trim($_POST["fname"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["lname"])))
    {
        $lname_err = "Please enter the your last name.";
    }
    else
    {
        $lname = trim($_POST["lname"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["email"])))
    {
        $email_err = "Please enter the your email address.";
    }
    else
    {
        $email = trim($_POST["email"]);
    }

    // Check input errors before updating the database
    if(empty($fname_err) && empty($lname_err) && empty($email_err))
    {
//        echo $_SESSION["id"];
        // Prepare an update statement
        $sql = "UPDATE users SET fname = ?, lname = ?, email = ? WHERE id = ?";

        if($stmt = $mysqli->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi", $param_fname, $param_lname, $param_email, $param_id);

            // Set parameters
            $param_fname = $fname;
            $param_lname = $lname;
            $param_email = $email;
            $param_id    = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if($stmt->execute())
            {
                // Profile info updated successfully, and redirect to index page
                header("location: ../index.php");
                exit();
            }
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}