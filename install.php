<?php
/**
 * file name install.php
 * use for create admin
 * require CheckPassword.php class to verify password
 * get admin name, email address and password and store in admin table
 * delete the file after create admin
 */

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if(isset($_POST['submit'])) {
        include_once './admin/includes/connection.php';
        $conn = db_connect("read");

        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $retyped = trim($_POST['con_password']);

        require_once './CheckPassword.php';

        $usernameMinChars = 6;
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email address '$email' is considered invalid";
        }

        if (strlen($username) < $usernameMinChars) {
            $errors[] = "Username must be at least $usernameMinChars characters.";
        }
        if (preg_match('/\s/', $username)) {
            $errors[] = 'Username should not contain spaces.';
        }
        $checkPwd = new CheckPassword($password, 6);
        //$checkPwd->requireMixedCase();
        //$checkPwd->requireNumbers(2);
        //$checkPwd->requireSymbols();
        $passwordOK = $checkPwd->check();

        if (!$passwordOK) {
            $errors = array_merge($errors, $checkPwd->getErrors());
        }
        if ($password != $retyped) {
            $errors[] = "Your passwords don't match.";
        }
        if (!$errors) {
            $password = password_hash($password, PASSWORD_DEFAULT);


            $sql = 'INSERT INTO admin (user_name,email,password) VALUES (?, ?, ?)';

            $stmt = $conn->stmt_init();
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('sss', $username, $email, $password);
                $stmt->execute();
            }

            if ($stmt->affected_rows == 1) {
                header('Location: ./admin/index.php');
            } elseif ($stmt->errno == 1062) {
                $errors[] = "$username is already in use. Please choose another username.";
            } else {
                $errors[] = $stmt->error;
            }

            $stmt->close();

        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>E-SHOP :: Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <style>
            *{
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
                box-sizing: border-box;
            }
            input{
                float: right;
                border: 1px solid #dddddd;
                border-radius: 3px;
                padding: 5px 10px;
                width: 250px;
            }
            lebel{
                float: left;
            }
            .error{
                color: #ff0000;
            }
        </style>
    </head>
    <body>
    <div style="margin: 0 auto; width: 450px; border: 1px solid #e2e6ea; border-radius: 3px; padding: 20px;  margin-top: 100px;">
        <?php
            if (isset($errors)&&!empty($errors)) {
                echo '<ul>';
                foreach ($errors as $error) {
                    echo '<li class="error">'.$error.'</li>';
                }
                echo '</ul>';
            }
        ?>
        <form action="" method="post" style="overflow: hidden;">
            <p>
                <label for="username">Username: </label>
                <input type="text" name="username" placeholder="Username">
            </p>
            <p>
                <label for="email">Email: </label>
                <input type="email" name="email" placeholder="Email">
            </p>
            <p>
                <label for="password">Password: </label>
                <input type="password" name="password" placeholder="password">
            </p>

            <p>
                <label for="con_pass">Confirm Password: </label>
                <input type="password" name="con_password" placeholder="Confirm password">
            </p>
            <p>
                <input type="submit" value="submit" name="submit" style="width: 80px;">
            </p>
        </form>
    </div>
    </body>
</html>