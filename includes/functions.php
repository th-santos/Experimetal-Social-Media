<?php

function moments($seconds)
{
    if ($seconds < 60 * 60 * 24 * 30) {
        return "within the month";
    }

    return "a while ago";
}

function getPosts($lines)
{
    $posts = [];

    $importantPriority  = [];
    $highPriority       = [];
    $normalPriority     = [];

    foreach ($lines as $line) {
        $post = validatePost($line);
        if ($post != false) {
            switch ($post['priority']) {
                    case 3:
                        $normalPriority[] = $post;
                        break;
                    case 2:
                        $highPriority[] = $post;
                        break;
                    case 1:
                        $importantPriority[] = $post;
                        break;
                }
        }
    }

    $posts = array_merge($importantPriority, $highPriority, $normalPriority);

    return $posts;
}

function searchPosts($searcTerm, $lines)
{
    $posts = [];

    $importantPriority = [];
    $highPriority   = [];
    $normalPriority = [];

    foreach ($lines as $line) {
        $post = validatePost($line);

        // 'strtolower' returns the position of where the needle exists 
        // relative to the beginning of the haystack string (independent of offset).
        // Also note that string positions start at 0, and not 1.
        // Returns FALSE if the needle was not found.
        // (best !== or === instead != or ==)

        if ($post !== false && strpos(strtolower($post['comment']), strtolower(trim($searcTerm))) !== false) {
            switch ($post['priority']) {
                case 3:
                    $normalPriority[] = $post;
                    break;
                case 2:
                    $highPriority[] = $post;
                    break;
                case 1:
                    $importantPriority[] = $post;
                    break;
            }
        }
    }

    $posts = array_merge($importantPriority, $highPriority, $normalPriority);

    return $posts;
}

function validatePost($post)
{
    $valid = [];

    $fields = preg_split("/\|/", $post);

    if (count($fields) == 7) {
        $firstName  = trim($fields[0]);
        $lastName   = trim($fields[1]);
        $title      = trim($fields[2]);
        $comment    = trim($fields[3]);
        $priority   = trim($fields[4]);
        $filename   = trim($fields[5]);
        $time       = trim($fields[6]);

        if ($firstName == '' ||
            $lastName == '' ||
            $title    == '' ||
            $comment  == '' ||
            $priority == '' ||
            $filename == '' ||
            $time     == '') {
            $valid = false;
        } elseif (!file_exists('uploads/'.$filename)) {
            $valid = false;
        } else {
            $valid['firstName'] = $firstName;
            $valid['lastName']  = $lastName;
            $valid['title']     = $title;
            $valid['comment']   = $comment;
            $valid['priority']  = $priority;
            $valid['filename']  = $filename;
            $valid['time']      = $time;
        }
    }

    return $valid;
}

function filterPost($post)
{
    $author     = trim($post['firstName']) . ' ' . trim($post['lastName']);
    $title      = trim($post['title']);
    $comment    = trim($post['comment']);
    $priority   = trim($post['priority']);
    $filename   = trim($post['filename']);
    $postedTime = trim($post['time']);

    $filteredPost['author']     = ucwords(strtolower($author));
    $filteredPost['moment']     = moments(time() - $postedTime);
    $filteredPost['title']      = trim($title);
    $filteredPost['comment']    = trim($comment);
    $filteredPost['priority']   = trim($priority);
    $filteredPost['filename']   = trim($filename);
    $filteredPost['postedTime'] = date('l F \t\h\e dS, Y', $postedTime);
    $filteredPost['searchResultsPostedTime'] = date('M d, \'y', $postedTime);

    return $filteredPost;
}

function validateFields($input)
{
    $valid = [];

    $firstName  = trim($input['firstName']);
    $lastName   = trim($input['lastName']);
    $title      = trim($input['title']);
    $comment    = trim($input['comment']);
    $priority   = trim($input['priority']);

    if ($firstName == '' ||
        $lastName == '' ||
        $title    == '' ||
        $comment  == '' ||
        $priority == '') {
        $valid = false;
    } elseif (!preg_match("/^[A-Z]+$/i", $firstName) || !preg_match("/^[A-Z]+$/i", $lastName) || !preg_match("/^[A-Z]+$/i", $title)) {
        $valid = false;
    } elseif (preg_match("/<|>/", $comment)) {
        $valid = false;
    } elseif (!preg_match("/^[0-9]{1}$/i", $priority)) {
        $valid = false;
    } else {
        $valid['firstName'] = $firstName;
        $valid['lastName'] = $lastName;
        $valid['title'] = $title;
        $valid['comment'] = $comment;
        $valid['priority'] = $priority;
    }

    return $valid;
}

function isValidFile($fileInfo)
{
    if ($fileInfo['type'] == 'image/jpeg') {
        return true;
    }

    return false;
}

function isValidSearchTerm($term)
{
    $term = trim($term);

    if (preg_match("/^[A-Z]+$/i", $term)) {
        return true;
    }

    return false;
}

function insertPost($data, $_db_table, $_link)
{
    $firstName = trim(ucwords(strtolower($data['firstName'])));
    $lastName = trim(ucwords(strtolower($data['lastName'])));
    $title = trim($data['title']);
    $comment = trim($data['comment']);
    $priority = trim($data['priority']);
    $fileName = md5(time().$data['firstName'].$data['lastName']) . '.jpg';
    $time = time();

    move_uploaded_file($data['file'], 'uploads/'.$fileName);

    // perform a query
    $_query = "INSERT INTO $_db_table (firstname, lastname, title, comment, priority, filename, time) VALUES ('$firstName', '$lastName', '$title', '$comment', '$priority', '$fileName', '$time');";
    mysqli_query($_link, $_query) or exit(mysqli_error($_link));
}

function checkSignUp($data)
{
    $valid = false;

    $data = formatAll($data);
        
    // if any of the fields are missing, return an error
    if (empty($data['firstName']) ||
        empty($data['lastName']) ||
        empty($data['password']) ||
        empty($data['phoneNumber']) ||
        empty($data['dob'])) {
        $valid = "All inputs are required.";
    } elseif (!preg_match("/^[A-Z]+$/i", $data['firstName'])) {
        $valid = 'First Name needs to be alphabetical only.';
    } elseif (!preg_match("/^[A-Z]+$/i", $data['lastName'])) {
        $valid = 'Last Name needs to be alphabetical only';
    } elseif (!preg_match("/^.*([0-9]+.*[A-Z])|([A-Z]+.*[0-9]+).*$/i", $data['password'])) {
        $valid = 'Password must contain at least a number and a letter.';
    } elseif (!preg_match("/^((\([0-9]{3}\))|([0-9]{3}))?( |-)?[0-9]{3}( |-)?[0-9]{4}$/", $data['phoneNumber'])) {
        $valid = 'Phone Number must be in the format of (000) 000 0000.';
    } elseif (!preg_match("/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)-[0-9]{2}-[0-9]{4}$/i", $data['dob'])) {
        $valid = 'Date of Birth must be in the format of MMM-DD-YYYY.';
    } else {
        $valid = true;
    }
    
    return $valid;
}

function formatAll($data)
{
    $formatData = [];

    foreach ($data as $key => $value) {
        if ($key != 'password') {
            $formatData[$key] = trim($value);
        } else {
            $formatData[$key] = $value;
        }
    };

    return $formatData;
}
