<?php
// Include config file
require_once "../config/db.php";

// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    session_start();

    // Validate first name
    if(empty(trim($_POST["fname"])))
    {
        $fname_err = "Please enter your first name.";
        $_SESSION["fname_err"] = $fname_err;
        header("location: ../register.php");
    }
    else
    {
        $fname = trim($_POST["fname"]);
    }

    // Validate last name
    if(empty(trim($_POST["lname"])))
    {
        $lname_err = "Please enter your last name.";
        $_SESSION["lname_err"] = $lname_err;
        header("location: ../register.php");
    }
    else
    {
        $lname = trim($_POST["lname"]);
    }

    // Validate email
    if(empty(trim($_POST["email"])))
    {
        $email_err = "Please enter a email.";
        $_SESSION["email_err"] = $email_err;
        header("location: ../register.php");
    }
    else
    {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if($stmt = $mysqli->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if($stmt->execute())
            {
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 1)
                {
                    $email_err = "This email is already taken.";
                    $_SESSION["email_err"] = $email_err;
                    header("location: ../register.php");
                }
                else
                {
                    $email = trim($_POST["email"]);
                }
            }
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if(empty(trim($_POST["password"])))
    {
        $password_err = "Please enter a password.";
        $_SESSION["password_err"] = $password_err;
        header("location: ../register.php");
    }
    elseif(strlen(trim($_POST["password"])) < 6)
    {
        $password_err = "Password must have atleast 6 characters.";
        $_SESSION["password_err"] = $password_err;
        header("location: ../register.php");
    }
    else
    {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm"])))
    {
        $confirm_password_err = "Please confirm password.";
        $_SESSION["confirm_password_err"] = $confirm_password_err;
        header("location: ../register.php");
    }
    else
    {
        $confirm_password = trim($_POST["confirm"]);
        if(empty($password_err) && ($password != $confirm_password))
        {
            $confirm_password_err = "Password did not match.";
            $_SESSION["confirm_password_err"] = $confirm_password_err;
            header("location: ../register.php");
        }
    }

    // Validate photo
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0)
    {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");


        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed))
        {
            $photo_err = "Error: Please select a valid file format.";
            $_SESSION["photo_err"] = $photo_err;
            header("location: ../register.php");
            die();
        }

        // Verify file size - 2MB maximum
        $maxsize = 2 * 1024 * 1024;
        if($filesize > $maxsize)
        {
            $photo_err = "Error: File size is larger than the allowed limit.";
            $_SESSION["photo_err"] = $photo_err;
            header("location: ../register.php");
            die();
        }

        // Verify MYME type of the file
        if(in_array($filetype, $allowed))
        {
            // Check whether file exists before uploading it
            if(file_exists("../assets/upload/" . $filename))
            {
                $photo_err = $filename . " is already exists.";
                $_SESSION["photo_err"] = $photo_err;
                header("location: ../register.php");
            }
            else
            {
                $n=10;
                $characters = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';

                for ($i = 0; $i < $n; $i++)
                {
                    $index = rand(0, strlen($characters) - 1);
                    $randomString .= $characters[$index];
                }
                $time = date('dmyHis');

                $photo = $randomString.$time.'.'.$ext;
                move_uploaded_file($_FILES["photo"]["tmp_name"], "../assets/upload/" . $photo);
            }
        }
        else
        {
            move_uploaded_file($_FILES["photo"]["tmp_name"], "../assets/upload/" . $filename);
            $photo_err = "There was a problem uploading your file. Please try again.";
            $_SESSION["photo_err"] = $photo_err;
            header("location: ../register.php");
        }
    }
    else
    {
        switch ($_FILES["photo"]["error"]) {
            case 1:
                $photo_err = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
                break;
            case 2:
                $photo_err = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                break;
            case 3:
                $photo_err = 'The uploaded file was only partially uploaded.';
                break;
            case 4:
                $photo_err = 'No file was uploaded.';
                break;
            case 6:
                $photo_err = 'Missing a temporary folder.';
                break;
            case 7:
                $photo_err = 'Failed to write file to disk.';
                break;
            case 8:
                $photo_err = 'A PHP extension stopped the file upload.';
                break;
//            default:
//                echo "Your favorite color is neither red, blue, nor green!";
        }
//        $photo_err = "Error: " . $_FILES["photo"]["error"];
        $_SESSION["photo_err"] = $photo_err;
        header("location: ../register.php");
    }

    // Check input errors before inserting in database
    if(empty($fname_err) && empty($lname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($photo_err))
    {
        $sql = "INSERT INTO users (email, password, fname, lname, profile_image) VALUES (?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_email, $param_password, $param_fname, $param_lname, $param_photo);

            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_fname = $fname;
            $param_lname = $lname;
            $param_photo = $photo;

            // Attempt to execute the prepared statement
            if($stmt->execute())
            {
                // Redirect to login page
                header("location: ../login.php");
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