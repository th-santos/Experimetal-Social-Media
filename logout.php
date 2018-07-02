<?php
    // destroy the current session
    session_destroy();

    // redirect
    header('Location: login.php');
    exit();