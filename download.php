<?php
    // GET
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['filename'])) {
        $filename = $_GET['filename'];
        $image_path = "./uploads/$filename";
        
        if (file_exists($image_path)) {
            // set the content type header
            header('Content-Type: image/jpeg');
            header("Content-Disposition: attachment; filename=$filename");

            // get image
            $image = imagecreatefromjpeg($image_path);
            
            // output the image and free up the memory
            imagejpeg($image);
            imagedestroy($image);
        }
    } else {
        // redirect
        header('Location: index.php');
        exit();
    }