<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/* fix problem with file paths */
$maindir = $_GET['dir'] ?? '.';
if ($maindir === false || !is_dir($maindir)) {
    echo "Invalid directory.";
    die();
}
$imageDirs = glob($maindir . '/*', GLOB_ONLYDIR);

function imWidth($a, $b) {
    $sizeA = getimagesize($a);
    $sizeB = getimagesize($b);
    if ($sizeA && $sizeB) {
        return ($sizeA[0]) <=> ($sizeB[0] );
    }
    return 0;
}   

function getSmallest($directory) {
    $globString = $directory . "/*.{jpg,jpeg,JPG JPEG}";
    $imageFiles = glob($globString, GLOB_BRACE);
    usort($imageFiles, "imWidth");
    echo("<details>");
    foreach($imageFiles as $imageFile) {
        $size = getImagesize($imageFile);
        echo ("<p>");
        echo ($imageFile . "&nbsp;&nbsp;");
        echo ($size[0] . 'x' . $size[1]);
        echo ("</p>");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Browser</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <h1>File Browser</h1>
    <ul>
        <?php 
        foreach ($imageDirs as $imageDir) {
            echo ('<li>');
            echo ('<p>' . $imageDir . '<p>');
            getSmallest($imageDir);
            echo ('</li>');   

        }
    
        ?>
    </ul>
</body></html>


