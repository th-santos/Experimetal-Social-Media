<?php
    session_start();
    date_default_timezone_set('America/Vancouver');
    require('includes/functions.php');

    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        // redirect
        header('Location: login.php');
        exit();
    }
    
    // echo '<pre>';
    // var_dump($_SESSION);
    // echo '</pre>';

    if (isset($_SESSION['firstName'])) {
        $firstName = trim($_SESSION['firstName']);
    } else {
        $firstName = '';
    }
    
    if (isset($_SESSION['lastName'])) {
        $lastName = trim($_SESSION['lastName']);
    } else {
        $lastName = '';
    }

    // mysql settings
    $hostname = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'assignment02';
    $db_table = 'posts';
    
    // estabilish connection and get the mysqli handler
    $link = mysqli_connect($hostname, $db_username, $db_password, $db_name);
    
    // check connection
    if (!$link) {
        printf("Connection failed: %s.", mysqli_connect_error());
        exit();
    }

    // POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['title']) && isset($_POST['comment']) && isset($_POST['priority'])) {
        $fieldInput = validateFields($_POST);
        $fileInput  = isValidFile($_FILES['file']);

        // prevent to resend the same data
        unset($_POST);

        if ($fieldInput != false && $fileInput != false) {
            $fieldInput['file'] = $_FILES['file']['tmp_name'];
            unset($_FILES);
            
            insertPost($fieldInput, $db_table, $link);

            $_SESSION['message'] = 'Thank you ' . $firstName . ' ' . $lastName . '! ' . date('F dS, Y', $time);

            // redirect
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            $_SESSION['message'] = 'Invalid input!';
        }
    }
    
    // perform a query
    $query = "SELECT * FROM $db_table;";
    $result = mysqli_query($link, $query) or exit(mysqli_error($link));

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        // echo '<pre>';
        // var_dump($row);
        // echo '</pre>';

        $lines[] = "$row[firstname]|$row[lastname]|$row[title]|$row[comment]|$row[priority]|$row[filename]|$row[time]";
    };

    // close connection
    mysqli_free_result($result);    // free result set
    mysqli_close($link);            // close connection

    if (isset($lines) && count($lines) > 0) {
        $posts = getPosts($lines);
        // echo '<pre>';
        // var_dump($posts);
        // echo '</pre>';
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
            <div class="col-md-6 col-md-offset-3">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h3 class="login-panel text-center text-muted">It is now <?php echo date('l F \t\h\e dS, Y', time());?></h3>
                <?php
                    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
                    ?>
                        <div class="alert alert-warning alert-dismissable text-center">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $_SESSION['message']; ?>
                        </div>
                    <?php
                        unset($_SESSION['message']);
                    }
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <button class="btn btn-default" data-toggle="modal" data-target="#newPost"><i class="fa fa-comment"></i> New Post</button>
                <a href="search.php" class="btn btn-default"><i class="fa fa-search"> </i> Search</a>
                <a href="logout.php" class="btn btn-default pull-right"><i class="fa fa-sign-out"> </i> Logout</a>
                <hr/>
            </div>
        </div>

        <?php
            if (isset($posts) && count($posts) > 0) {
                foreach ($posts as $post) {
                    $filteredPost = filterPost($post);

                    $panelTag = '';
                    if ($filteredPost['priority'] == 1) {
                        $panelTag = 'panel-danger';
                    } elseif ($filteredPost['priority'] == 2) {
                        $panelTag = 'panel-warning';
                    } else {
                        $panelTag = 'panel-info';
                    }

                    echo '
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="panel ' . $panelTag . '">
                                    <div class="panel-heading">
                                        <span>
                                            '. $filteredPost['title'] .'
                                        </span>
                                        <span class="pull-right text-muted">
                                            ' . $filteredPost['moment'] . '
                                        </span>
                                    </div>
                                    <div class="panel-body">
                                        <p class="text-muted">
                                            Posted on ' . $filteredPost['postedTime'] .' 
                                        </p>
                                        <p>
                                            ' . $filteredPost['comment'] . '
                                        </p>
                                        <div class="img-box">
                                            <img class="img-thumbnail img-responsive" src="uploads/'.$filteredPost['filename'].'"/>
                                            <a href="download.php?filename=' . $filteredPost['filename'] . '" class="btn btn-default pull-right"><i class="fa fa-download"> </i> Download</a>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <p>
                                            By ' . $filteredPost['author'] . '
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
                }
            } else {
                echo '
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span class="pull-right text-muted">
                                    </span>
                                </div>
                                <div class="panel-body">
                                    <span class="text-muted">
                                        No posts found!
                                    </span>
                                </div>
                                <div class="panel-footer">
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            }
        ?>
    </div>
</div>

<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form role="form" method="post" action="index.php" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">New Post</h4>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <input class="form-control" placeholder="First Name" name="firstName" 
                                <?php
                                    if (!empty($firstName)) {
                                        echo 'value="' . $firstName . '"';
                                    }
                                ?>
                            >
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Last Name" name="lastName"
                                <?php
                                    if (!empty($lastName)) {
                                        echo 'value="' . $lastName . '"';
                                    }
                                ?>
                            >
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input class="form-control" placeholder="" name="title">
                        </div>
                        <div class="form-group">
                            <label>Comment</label>
                            <textarea class="form-control" rows="3" name="comment"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Priority</label>
                            <select class="form-control" name="priority">
                                <option value="1">Important</option>
                                <option value="2">High</option>
                                <option value="3">Normal</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="file" />
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Post!"/>
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
