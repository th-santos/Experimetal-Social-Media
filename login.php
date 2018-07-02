<?php
    session_start();
    $message = '';

    if (isset($_COOKIE['phoneNumber'])) {
        $phoneNumber = $_COOKIE['phoneNumber'];
    } else {
        $phoneNumber = '';
    }

    // POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['phoneNumber']) && isset($_POST['password'])) {
        $phoneNumber = trim($_POST['phoneNumber']);
        $password = $_POST['password'];

        if (isset($_POST['remember']) && $_POST['remember'] == 1) {
            setcookie('phoneNumber', $phoneNumber, time() + 60 * 60 * 24 * 20);    // 60 seconds, 60 minutes, 24 hours, 20 days
        } else {
            setcookie('phoneNumber', null, time() - 3600);
        }

        // prevent to resend the same data
        unset($_POST);

        if (empty($phoneNumber) || empty($password)) {
            $message = '<div class="alert alert-danger text-center">
                            All inputs are required.
                        </div>';
        } elseif (!preg_match("/^((\([0-9]{3}\))|([0-9]{3}))?( |-)?[0-9]{3}( |-)?[0-9]{4}$/", $phoneNumber)) {
            $message = '<div class="alert alert-danger text-center">
                            Phone Number must be in the format of (000) 000 0000.
                        </div>';
        } else {
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
            $query = "SELECT password, firstname, lastname FROM $db_table WHERE phoneNumber = '$phoneNumber';";
            $result = mysqli_query($link, $query) or exit(mysqli_error($link));

            // echo '<pre>';
            // var_dump($result);
            // echo '</pre>';

            $validLogin = false;
        
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                // echo '<pre>';
                // var_dump($row['password']);
                // echo '</pre>';

                if ($row['password'] === $password) {
                    $validLogin = true; // ********
                    
                    // save session data
                    $_SESSION['login'] = true;
                    $_SESSION['firstName'] = $row['firstname'];
                    $_SESSION['lastName'] = $row['lastname'];

                    break;
                }
            };

            if ($validLogin) {
                // close connection
                mysqli_free_result($result);    // free result set
                mysqli_close($link);            // close connection
                
                // redirect
                header('Location: index.php');
                exit();
            } else {
                $message = '<div class="alert alert-danger text-center">
                                The phone number and password combination given do not match.<br> 
                                Plese try again or <a href="./signup.php">sign up</a>.
                            </div>';
            }
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
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form name="login" role="form" action="login.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control"
                                           value="<?php echo $phoneNumber;?>"
                                           name="phoneNumber"
                                           placeholder="Phone Number"
                                           type="text"
                                        <?php echo empty($phoneNumber) ? 'autofocus' : ''; ?>
                                    />
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                           name="password"
                                           placeholder="Password"
                                           type="password"
                                        <?php echo empty($phoneNumber) ? '' : 'autofocus'; ?>
                                    />
                                </div>
                                <div class="form-group">
                                    <input type="checkbox"
                                           value="1"
                                           name="remember"
                                        <?php echo empty($phoneNumber) ? '' : 'checked'; ?>
                                    />
                                    Remember Me
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Login"/>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <a class="btn btn-sm btn-default" href="signup.php">Sign Up</a>
            </div>
        </div>

    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
