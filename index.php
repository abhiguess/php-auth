<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Authentication</title>

    <link href="assets/css/tailwind.min.css" rel="stylesheet">
    <link href="assets/css/auth.css" rel="stylesheet">

    <script src="assets/js/alpine.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <nav class="flex items-center justify-between flex-wrap bg-white shadow p-3">
        <div class="flex items-center flex-shrink-0 text-black mr-6 cursor-pointer" onclick="location.href='index.php'">
            <img src="assets/img/logo.svg" alt="" class="fill-current h-8 w-auto mr-2">
            <span class="font-semibold text-xl tracking-tight">Authentication</span>
        </div>
        <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
            <div class="lg:flex-grow">
            </div>
            <div>
                <?php if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) : ?>
                    <a href="login.php" class="float-right inline-block text-sm px-4 py-2 leading-none border border-blue-500 rounded bg-blue-500 text-white font-bold hover:border-blue-500 hover:text-blue-500 hover:bg-white mt-4 ml-3 lg:mt-0">Login</a>
                    <a href="register.php" class="float-right inline-block text-sm px-4 py-2 leading-none border border-blue-500 rounded bg-transparent text-blue-500 font-bold hover:border-transparent hover:text-white hover:bg-blue-500 mt-4 lg:mt-0">Register</a>
                <?php else: ?>
                    <a href="auth/logout.php" class="float-right inline-block text-sm px-4 py-2 leading-none border border-blue-500 rounded bg-blue-500 text-white font-bold hover:border-blue-500 hover:text-blue-500 hover:bg-white mt-4 ml-3 lg:mt-0">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="flex">
        <div class="w-full">
            <div class="bg-white shadow rounded-lg px-2 py-12 w-1/5 mx-auto mt-32">
                <img class="h-32 w-32 mb-8 rounded-full bg-gray-500 mx-auto shadow" src="assets/upload/<?php echo htmlspecialchars($_SESSION["profile"]); ?>">
                <div class="text-center">
                    <h2 class="text-lg"><?php echo htmlspecialchars($_SESSION["fname"]).' '.htmlspecialchars($_SESSION["lname"]); ?></h2>
                    <div class="text-purple-500"><?php echo htmlspecialchars($_SESSION["email"]); ?></div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>