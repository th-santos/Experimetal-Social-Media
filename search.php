<?php
    session_start();
    date_default_timezone_set('America/Vancouver');
    require('includes/functions.php');

    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        // redirect
        header('Location: login.php');
        exit();
    }

    $results = [];
    $term = '';
    $message = '';
    $count = 0;

    if (isset($_GET['search']) && isValidSearchTerm($_GET['search'])) {
        $term = trim($_GET['search']);

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

        // perform a query
        $query = "SELECT * FROM $db_table WHERE LOWER(comment) LIKE LOWER('%$term%');";
        $res = mysqli_query($link, $query) or exit(mysqli_error($link));

        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            $lines[] = "$row[firstname]|$row[lastname]|$row[title]|$row[comment]|$row[priority]|$row[filename]|$row[time]";
        };

        // close connection
        mysqli_free_result($res);    // free result set
        mysqli_close($link);            // close connection

        if (isset($lines) && count($lines) > 0) {
            $results = searchPosts($term, $lines);
            $count = count($results);
            // echo '<pre>';
            // var_dump($posts);
            // echo '</pre>';
        }

    } elseif (isset($_GET['search'])) {
        $message = '<div class="alert alert-warning alert-dismissable text-center">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                Invalid input!
                        </div>';
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
                <h3 class="login-panel text-center text-muted">Search</h3>
                <?php echo $message; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <a href="index.php" class="btn btn-default"><i class="fa fa-arrow-circle-left"> </i> Back</a>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form role="form" action="search.php" method="get">
                    <div class="form-group input-group">
                        <input type="text" value="<?php echo $term; ?>" placeholder="Search term" class="form-control" name="search" autofocus>
                        <span class="input-group-btn">
                            <!-- button type=submit to submit form-->
                            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Results
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Title</th>
                                    <th>Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if (count($results) > 0) {
                                        foreach ($results as $result) {
                                            $filteredPost = filterPost($result);
                                            $panelTag = '';
                                            if ($filteredPost['priority'] == 1) {
                                                $panelTag = 'danger';
                                            } elseif ($filteredPost['priority'] == 2) {
                                                $panelTag = 'warning';
                                            } else {
                                                $panelTag = 'info';
                                            }

                                            echo '
                                                <tr class="'.$panelTag.'">
                                                    <td>' . $filteredPost['author']     . '</td>
                                                    <td>' . $filteredPost['title']      . '</td>
                                                    <td>' . $filteredPost['searchResultsPostedTime'] . '</td>
                                                </tr>
                                            ';
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <p class="text-center text-muted">
                    Total results: <?php echo $count; ?>.
                </p>
            </div>
        </div>

    </div>
</div>

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
