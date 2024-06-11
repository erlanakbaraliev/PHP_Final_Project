<?php
session_start();
$loggedIn = false;
$exists = false;
$registered = false;

$errorsReg = [];
$errorsLog = [];

if ($_POST) {
    if ($_POST['type'] == 'logout' && isset($_SESSION['email'])) {
        session_destroy();
    }

    if ($_POST['type'] == 'login') {
        $users = json_decode(file_get_contents('data/users.json'), true);
        $found = false;

        if (!isset($_POST['email']) || trim($_POST['email']) === "") {
            $errorsLog[] = 'Email required';
        } else if (strlen($_POST['email']) < 4) {
            $errorsLog[] = 'Minimum email length must be 4';
        }

        if (!isset($_POST['password']) || trim($_POST['password']) === "") {
            $errorsLog[] = 'Password required';
        } else if (strlen($_POST['password']) < 4) {
            $errorsLog[] = 'Minimum password length must be 4';
        }

        foreach ($users as $user) {
            if ($user['email'] == $_POST['email'] && $user['password'] == $_POST['password']) {
                $found = true;
                break;
            }
        }

        if ($found) {
            $_SESSION['email'] = $_POST['email'];
        } else {
            $errorsLog[] = 'Wrong email or password';
        }
    } elseif ($_POST['type'] == 'registration') {
        $users = json_decode(file_get_contents('data/users.json'), true);

        if (count($users) > 0) {
            foreach ($users as $user) {
                if ($user['email'] == $_POST['email']) {
                    $exists = true;
                    break;
                }
            }
        }

        if (!isset($_POST['name']) || trim($_POST['name']) === "") {
            $errorsReg[] = "Name required";
        } else if (strlen($_POST['name']) < 4) {
            $errorsReg[] = 'Minimum name length must be 4';
        }

        if (!isset($_POST['email']) || trim($_POST['email']) === "") {
            $errorsReg[] = "Email required";
        } else if (strlen($_POST['email']) < 4) {
            $errorsReg[] = 'Minimum email length must be 4';
        }

        if (!isset($_POST['password']) || trim($_POST['password']) === "") {
            $errorsReg[] = "Password required";
        } else if (strlen($_POST['password']) < 4) {
            $errorsReg[] = 'Minimum password length must be 4';
        }

        if (!isset($_POST['password2']) || trim($_POST['password2']) === "") {
            $errorsReg[] = "Confirm the password";
        } else if ($_POST['password2'] !== $_POST['password']) {
            $errorsReg[] = "The passwords are not the same";
        }

        if (!$exists && count($errorsReg) == 0 && $_POST['password'] == $_POST['password2']) {
            $users[] = $_POST;

            $new_json = json_encode($users, JSON_PRETTY_PRINT);
            file_put_contents('data/users.json', $new_json);

            $registered = true;
        }
    }
}
if ($_POST && $_POST['type'] != 'logout' && isset($_SESSION['email'])) {
    $loggedIn = true;
}
if ($_GET && $_GET['type'] == 'logout' && isset($_SESSION['email'])) {
    session_destroy();
    $loggedIn = false;
    $found = false;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="forest">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body class="p-0">
    <div class="header w-full text-3xl bg-neutral p-5 font-bold text-neutral-content text-center ">
        Registration / Login
        <a class="btn btn-primary font-bold ml-10 mt-1" href="index.php">Main Page</a>
    </div>
    <?php if ($loggedIn): ?>
        <div class='p-5'>
            <div>You're logged in as <?= $_SESSION['email'] ?></div>
            <form action='login.php' method='post' novalidate>
                <input type='text' name='type' value='logout' hidden>
                <input type='submit' value='Log out' class='btn btn-primary'>
            </form>
        </div>
    <?php else: ?>
        <div class='grid grid-cols-2 w-8/12 mx-auto'>
            <form action="login.php" method="post" class='p-5' novalidate>
                <h1 class="text-3xl  p-5 font-bold">Register</h1>
                <label class="form-control w-full max-w-xs">
                    <div class="label">
                        <span class="label-text">Name</span>
                    </div>
                    <input type="text" name="name" placeholder="Type here" class="input input-bordered w-full max-w-xs">
                </label>

                <label class="form-control w-full max-w-xs">
                    <div class="label">
                        <span class="label-text">Email</span>
                    </div>
                    <input type="email" name="email" placeholder="Type here" class="input input-bordered w-full max-w-xs">
                </label>

                <label class="form-control w-full max-w-xs">
                    <div class="label">
                        <span class="label-text">Password</span>
                    </div>
                    <input type="password" name="password" placeholder="Type here" class="input input-bordered w-full max-w-xs">
                </label>

                <label class="form-control w-full max-w-xs">
                    <div class="label">
                        <span class="label-text">Confirm the password</span>
                    </div>
                    <input type="password" name="password2" placeholder="Type here" class="input input-bordered w-full max-w-xs">
                </label>

                <input type='submit' value="Registration" class='btn btn-primary mt-2'>
                <input type="text" value="registration" name='type' hidden>

                <?php if ($exists && count($_POST) > 0): ?>
                    <p class="text-red-500">An account with this email already exists</p>
                <?php endif; ?>
                <?php if (!$exists): ?>
                    <ul>
                        <?php foreach ($errorsReg as $error): ?>
                            <li class="text-red-500"><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif ?>
                <?php if ($registered): ?>
                    <p class="text-green-500">Successfully registered</p>
                <?php endif; ?>
            </form>

            <form action="login.php" method="post" class='p-5' novalidate>
                <h1 class="text-3xl  p-5 font-bold">Login</h1>
                <label class="form-control w-full max-w-xs">
                    <div class="label">
                        <span class="label-text">Email</span>
                    </div>
                    <input type="email" name="email" placeholder="Type here" class="input input-bordered w-full max-w-xs">
                </label>

                <label class="form-control w-full max-w-xs">
                    <div class="label">
                        <span class="label-text">Password</span>
                    </div>
                    <input type="password" name="password" placeholder="Type here" class="input input-bordered w-full max-w-xs">
                </label>
                <input type='submit' value="Login" class='btn btn-primary mt-2'>
                <input type="text" value="login" name='type' hidden>

                <ul>
                    <?php foreach ($errorsLog as $error): ?>
                        <li class="text-red-500"><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </form>
        </div>
    <?php endif; ?>
</body>

</html>
