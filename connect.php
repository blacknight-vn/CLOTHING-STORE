<?php

session_start();




// --------------------------------------------------------------------------------- //
// --------------------------------- PREPARE FOR FILE ------------------------------ //
// --------------------------------------------------------------------------------- //




$log = $_SESSION['log'] ?? false;

$_SESSION['log'] = $log;

$type = 'mysql';
$port = '3306';
$db = 'CLOTHING_STORE';
$charset = 'utf8mb4';
$host = 'localhost';

$username = 'root';
$password = '';

$dsn = "$type:host=$host;dbname=$db;port=$port;charset=$charset";
date_default_timezone_set('Asia/Ho_Chi_Minh');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];




// --------------------------------------------------------------------------------- //
// ------------------------------- CONNECT DATEBASE -------------------------------- //
// --------------------------------------------------------------------------------- //




try {
    $pdo = new pdo($dsn, $username, $password, $options);
} catch(PDOException $e) {
    throw new PDOException($e->getMessage(), $e->getCode());
}




// --------------------------------------------------------------------------------- //
// ------------------------------- FUNCTION PDO ------------------------------------ //
// --------------------------------------------------------------------------------- //




function pdo($pdo, $sql, array $arg = null) {
    if (!$arg) {
        $statement = $pdo->query($sql);
        return $statement;
    }
    ;

    $statement = $pdo->prepare($sql);
    $statement->execute($arg);
    return $statement;
} 


function checkTrue($a) {
    $log = $_SESSION['log'] ?? false;

    $_SESSION['log'] = $log;
    if ($a) {
        $id = $_SESSION['id'];
        header("Location: home.php?id=$id");
        exit;
    }
};

function checkFalse($a) {
    $log = $_SESSION['log'] ?? false;

    $_SESSION['log'] = $log;
    if (!($a)) {
        header('Location: login_and_sign_up.php');
        exit;
    }
}


function create_name($filename, $path) {
    $basename = pathinfo($filename, PATHINFO_FILENAME);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $basename = preg_replace('/[^A-z0-9]/', '-', $basename);
    $basename = preg_replace('/[\!\#\$\@\%\^\&\*\(\)\~]/', '-', $basename);
    $filename = $basename . '.' . $ext;
    $i = 0;

    while (file_exists($path . $filename)) {
        $i++;
        $filename = $basename . '(' . $i . ')' . '.' . $ext;
    }
    ;
    return $filename;
}
;


function hanlde_image($orig_path, $new_path, $max_width, $max_height) {

    $info = getimagesize($orig_path);
    $media_type = $info['mime'];
    $orig_width = $info[0];
    $orig_height = $info[1];
    $ratio = round($orig_width / $orig_height);

    if ($ratio > 1) {
        $new_width = $max_width;
        $new_height = $new_width / $ratio;

    } else {
        $new_height = $max_height;
        $new_width = $ratio * $new_height;

    }
    ;

    switch ($media_type) {
        case 'image/jpeg':
            $orig = imagecreatefromjpeg($orig_path);
            break;
        case 'image/gif':
            $orig = imagecreatefromgif($orig_path);
            break;
        case 'image/png':
            $orig = imagecreatefrompng($orig_path);
            break;
    }
    ;

    $new = imagecreatetruecolor($new_width, $new_height);

    imagecopyresampled($new, $orig, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);

    switch ($media_type) {
        case 'image/jpeg':
            $return = imagejpeg($new, $new_path);
            break;

        case 'image/png':
            $return = imagepng($new, $new_path);
            break;

        case 'image/gif':
            $return = imagegif($new, $new_path);
            break;
    }

    return $return;
};



?>