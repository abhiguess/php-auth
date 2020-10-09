<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<?php
include 'config/db.php';

// Prepare a select statement
$sql = "SELECT * FROM users WHERE id = ?";

if($stmt = $mysqli->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("i", $param_id);

    // Set parameters
    $param_id = $_SESSION["id"];

    // Attempt to execute the prepared statement
    if($stmt->execute()){
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
            $row = $result->fetch_array(MYSQLI_ASSOC);

            // Retrieve individual field value
            $fname = $row["fname"];
            $lname = $row["lname"];
            $email = $row["email"];
            $profile = $row["profile_image"];
        }
        else
        {
            // URL doesn't contain valid id parameter. Redirect to error page
            header("location: error.php");
            exit();
        }

    }
    else
    {
        echo "Oops! Something went wrong. Please try again later.";
    }
}

// Close statement
$stmt->close();

// Close connection
$mysqli->close();

?>

<?php include 'include/header.inc.php'; ?>

    <div class="flex">
        <div class="w-full">
            <div class="bg-white shadow rounded-lg px-2 py-6 w-1/5 mx-auto mt-32">
                <img class="h-32 w-32 mb-8 rounded-full bg-gray-500 mx-auto shadow" src="assets/upload/<?php echo htmlspecialchars($profile); ?>">
                <div class="text-center">
                    <h2 class="text-lg"><?php echo htmlspecialchars($fname).' '.htmlspecialchars($lname); ?></h2>
                    <div class="text-purple-500 mb-4"><?php echo htmlspecialchars($email); ?></div>
                    <a href="edit.php" class="text-blue-500 font-bold">Edit</a>
                </div>
            </div>
        </div>
    </div>

<?php include 'include/footer.inc.php'; ?>