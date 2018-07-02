<?php
    session_start();
    require("includes/functions.php");

    $message = '';
    $firstName = '';
    $lastName = '';
    $phoneNumber = '';
    $dob = '';

    if (isset($_COOKIE['firstName'])) {
        $firstName = $_COOKIE['firstName'];
    }

    if (isset($_COOKIE['lastName'])) {
        $lastName = $_COOKIE['lastName'];
    }

    if (isset($_COOKIE['phoneNumber'])) {
        $phoneNumber = $_COOKIE['phoneNumber'];
    }

    if (isset($_COOKIE['dob'])) {
        $dob = $_COOKIE['dob'];
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['password']) && isset($_POST['phoneNumber']) && isset($_POST['dob'])) {
        $check = checkSignUp($_POST);

        if ($check === true) {
            $firstName = trim(ucwords(strtolower($_POST['firstName'])));
            $lastName = trim(ucwords(strtolower($_POST['lastName'])));
            $password = $_POST['password'];
            $phoneNumber = trim($_POST['phoneNumber']);
            $dob = trim($_POST['dob']);
            
            setcookie('firstName', $firstName, time() + 60 * 60);
            setcookie('lastName', $lastName, time() + 60 * 60);
            setcookie('phoneNumber', $phoneNumber, time() + 60 * 60);
            setcookie('dob', $dob, time() + 60 * 60);
            
            // mysql settings
            $hostname = 'localhost';
            $db_username = 'root';
            $db_password = '';
            $db_name = 'assignment02';
            $db_table = 'logins';
            
            // format data before save it in the database
            $password = md5($password);
            $phoneNumber = preg_replace('/^.?(\d{3}).?.?(\d{3}).?(\d{4})$/', '$1$2$3', $phoneNumber);
            
            // estabilish connection and get the mysqli handler
            $link = mysqli_connect($hostname, $db_username, $db_password, $db_name);
            
            // check connection
            if (!$link) {
                printf("Connection failed: %s.", mysqli_connect_error());
                exit();
            }
            
            // perform a query
            $query = "INSERT INTO $db_table (firstname, lastname, password, phoneNumber, dob) VALUES ('$firstName', '$lastName', '$password', '$phoneNumber', '$dob');";
            mysqli_query($link, $query) or exit(mysqli_error($link));
            
            // close connection
            mysqli_close($link);            // close connection

            // save session data
            $_SESSION['login'] = true;
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName'] = $lastName;
            
            // redirect
            header('Location: index.php');
            exit();
        } else {
            $message = '<div class="alert alert-danger text-center">
                            '.$check.' 
                        </div>';
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>COMP 3015</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div id="wrapper">

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <h1 class="login-panel text-center text-muted">COMP 3015</h1>

                <?php echo $message; ?>

                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Create Account</h3>
                    </div>
                    <div class="panel-body">
                        <form name="signup" role="form" action="signup.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control"
                                        value="<?php echo $firstName;?>"
                                        name="firstName"
                                        placeholder="First Name"
                                        type="text"
                                        autofocus
                                    />
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                        value="<?php echo $lastName;?>"
                                        name="lastName"
                                        placeholder="Last Name"
                                        type="text"
                                    />
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                        name="password"
                                        placeholder="Password"
                                        type="password"
                                    />
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                        value="<?php echo $phoneNumber;?>"
                                        name="phoneNumber"
                                        placeholder="Phone Number"
                                        type="text"
                                    />
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                        value="<?php echo $dob;?>"
                                        name="dob"
                                        placeholder="Date of Birth"
                                        type="text"
                                    />
                                </div>
                                <input type="submit" class="btn btn-lg btn-info btn-block" value="Sign Up!"/>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <a class="btn btn-sm btn-default" href="login.php">Login</a>
            </div>
        </div>

    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
