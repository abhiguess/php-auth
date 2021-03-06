<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
{
    header("location: index.php");
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

    <script src="assets/js/alpine.min.js" defer></script>
</head>
<body class="bg-gray-100">
<nav class="flex items-center justify-between flex-wrap bg-white shadow p-3 cursor-pointer" onclick="location.href='index.php'">
    <div class="flex items-center flex-shrink-0 text-black mr-6">
        <img src="assets/img/logo.svg" alt="" class="fill-current h-8 w-auto mr-2">
        <span class="font-semibold text-xl tracking-tight">Authentication</span>
    </div>
    <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
        <div class="lg:flex-grow">
        </div>
        <div>
            <a href="login.php" class="float-right inline-block text-sm px-4 py-2 leading-none border rounded border-blue-500 bg-blue-500 text-white font-bold hover:border-blue-500 hover:text-blue-500 hover:bg-white mt-4 lg:mt-0">Login</a>
        </div>
    </div>
</nav>

<div class="flex">
    <div class="w-full">
        <div class="mt-32 mx-auto w-full lg:w-1/3 bg-orange-200 p-8 rounded shadow">
            <form action="<?php echo htmlspecialchars('auth/signup.php'); ?>" method="post" class="w-full max-w-lg" enctype="multipart/form-data">
                <div class="w-32 h-32 mx-auto rounded-full shadow -mt-24">
                    <div class="w-32 h-32 mb-1 border rounded-full overflow-hidden relative bg-gray-100">
                        <img id="image" class="object-cover w-full h-32" src="https://placehold.co/300x300/e2e8f0/e2e8f0" />

                        <div class="absolute top-0 left-0 right-0 bottom-0 w-full block cursor-pointer flex items-center justify-center" onClick="document.getElementById('fileInput').click()">
                            <button type="button"
                                    style="background-color: rgba(255, 255, 255, 0.65)"
                                    class="hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 text-sm border border-gray-300 rounded-lg shadow-sm"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-camera" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                    <path d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                    <circle cx="12" cy="13" r="3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <input name="photo" id="fileInput" accept="image/*" class="hidden" type="file" onChange="let file = this.files[0];
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            document.getElementById('image').src = e.target.result;
                            document.getElementById('image2').src = e.target.result;
                        };

                        reader.readAsDataURL(file);
				    ">

                    <?php if (!empty($_SESSION["photo_err"])): ?>
                        <p class="text-red-500 text-xs italic"><?php echo $_SESSION["photo_err"]; ?></p>
                    <?php endif; ?>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 mt-8">
                        <div class="w-full">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="fname">
                                First Name
                            </label>
                            <input id="fname" name="fname" type="text" placeholder="John" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        </div>
                        <?php if (!empty($_SESSION["fname_err"])): ?>
                            <p class="text-red-500 text-xs italic"><?php echo $_SESSION["fname_err"]; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="w-full md:w-1/2 px-3 mt-8">
                        <div class="w-full">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="lname">
                                Last Name
                            </label>
                            <input id="lname" name="lname" type="text" placeholder="Doe" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        </div>
                        <?php if (!empty($_SESSION["lname_err"])): ?>
                            <p class="text-red-500 text-xs italic"><?php echo $_SESSION["lname_err"]; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full px-3 mt-2">
                        <div class="w-full">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">
                                Email Address
                            </label>
                            <input id="email" name="email" type="email" placeholder="john@mail.com" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        </div>
                        <?php if (!empty($_SESSION["email_err"])): ?>
                            <p class="text-red-500 text-xs italic"><?php echo $_SESSION["email_err"]; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/2 px-3 mt-2">
                        <div class="w-full">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="password">
                                Password
                            </label>
                            <input id="password" name="password" type="password" placeholder="********" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        </div>
                        <?php if (!empty($_SESSION["password_err"])): ?>
                            <p class="text-red-500 text-xs italic"><?php echo $_SESSION["password_err"]; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="w-full md:w-1/2 px-3 mt-2">
                        <div class="w-full">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="password">
                                Confirm Password
                            </label>
                            <input id="confirm" name="confirm" type="password" placeholder="********" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        </div>
                        <?php if (!empty($_SESSION["confirm_password_err"])): ?>
                            <p class="text-red-500 text-xs italic"><?php echo $_SESSION["confirm_password_err"]; ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex mt-4">
                    <div class="w-full">
                        <button type="submit" class="bg-gray-800 hover:bg-black text-white w-full font-bold py-2 px-6 rounded">
                            Sign Up
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<?php if (!empty($_SESSION["other_err"])): ?>
<div class="alert flex flex-row items-center bg-red-200 p-5 rounded border-b-2 border-red-300 lg:w-1/4 absolute right-0  bottom-0 mr-8 mb-8">
    <div class="alert-icon flex items-center bg-red-100 border-2 border-red-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
        <span class="text-red-500">
            <svg fill="currentColor"
                 viewBox="0 0 20 20"
                 class="h-6 w-6">
                <path fill-rule="evenodd"
                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                      clip-rule="evenodd">
                </path>
            </svg>
        </span>
    </div>
    <div class="alert-content ml-4">
        <div class="alert-title font-semibold text-lg text-red-800">
            Error
        </div>
        <div class="alert-description text-sm text-red-600">
            <p class="text-red-500 text-xs italic"><?php echo $_SESSION["other_err"]; ?></p>
        </div>
    </div>
</div>
<?php endif; ?>
</body>
</html>

<?php session_destroy(); ?>