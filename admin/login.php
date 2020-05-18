<?php
/**
 * file name login.php
 * use for admin login
 * match the username and password and redirect admin home page (admin/index.php)
 */

    include '../config.php';
    $error = '';

    if (isset($_POST['login'])) {
        session_start();                /// start the session

        require_once './includes/connection.php';
        $conn = db_Connect('read');



        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        if(isset($_POST['cbx']))  $checkbox = $_POST['cbx'];
        else $checkbox = '';
        $redirect = './index.php';

        //$_SESSION['name'] = $username;


        /// get the password

        $sql = 'SELECT password FROM admin WHERE (user_name = ?)';

        $stmt = $conn->stmt_init();
        $stmt->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($storedPwd);
        $stmt->fetch();

        /// match the password
        if (password_verify($password, $storedPwd)) {
            $_SESSION['authenticated'] = 'aString';

            $_SESSION['user_name'] = $username;
            if($checkbox == "yes"){
                $_SESSION['start'] = time();
                $_SESSION['expire'] = $_SESSION['start'] + (30 * 24 * 60 * 60);
            }

            session_regenerate_id();

            header("Location: $redirect");
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= strtoupper($site_title);?> :: Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css" type="text/css">
</head>
<body>
<div class="container">
    <div class="row justify-content-center" style="padding-top:50px;padding-bottom:10px;">
        <div class="col-12 col-sm-8 col-xl-6">
            <h3 style="text-align:center;"><a href="../index.php"><?= strtoupper($site_title);?></a></h3>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-7 col-xl-5 border">
            <div style="padding-top:20px">
                <form method="post" action="">
                    <p style="padding-bottom: 10px; color: #ff0000;"><?php
                        if(isset($error)){
                            echo $error;
                        }
                        ?></p>
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="Username" name="username">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" placeholder="password" name="password">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="cbx" value="yes" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Remember me</label>
                    </div>
                    <div class="form-group" style="text-align:center;">
                        <input type="submit" class="btn btn-primary" value="Login" name="login">
                    </div>
                </form>

                <hr>
                <div style="text-align:right; padding-bottom:10px">
                    <!--
                    <a href="" style="text-align:right">Forget password</a>
                    -->
                </div>
                <div style="text-align:center; padding-bottom:10px;color:#ccc;font-size:13px;">
                    <p>By signing up, you agree to our <a href="">Terms of Service</a> and <a href="">Privacy Policy</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>