<?php
/* fix problem with file paths */
$dir = $_GET['dir'] ?? '.';
$dir = realpath($dir);
if ($dir === false || !is_dir($dir)) {
    echo "Invalid directory.";
    die();
}
$imageDirs = scandir($dir);


function imWidth($a, $b) {
    $sizeA = getimagesize($a);
    $sizeB = getimagesize($b);
    if ($sizeA && $sizeB) {
        return ($sizeA[0]) <=> ($sizeB[0] );
    }
    return 0;
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
            /* If $file is a directory, list the file and each image file it contains. */
            /* Skip the current and parent directory entries. */
            if ($imageDir === '.' || $imageDir === '..') {
                continue;
            }
            if (is_dir("$dir/$imageDir")) { 
                $jjj = "$dir/$imageDir";
                echo("<li><p>Directory " . "$dir/$imageDir" . "</p>");
        ?>                      
                    <?php
                    $subFiles = scandir("$dir/$imageDir");
                    $imageFiles = [];
                    foreach ($subFiles as $subFile) {
                        if (is_file("$dir/$imageDir/$subFile") && preg_match('/\.(jpg|jpeg|png|gif)$/i', $subFile)) {
                            $imageFiles[] = $subFile;
                        }
                    }
                    usort($imageFiles, 'imWidth');
                    foreach ($imageFiles as $subFile) {
                        echo "<p><span><a href=\"$dir/$imageDir/$subFile\">$subFile</a></span>";
                        // list the dimensions of the image
                        $imageSize = getimagesize("$dir/$imageDir/$subFile");
                        if ($imageSize) {
                            echo "<span>&nbsp;" . $imageSize[0] . "x" . $imageSize[1] . "</span>";
                        }
                        else {
                            echo ("<span>File has no dimensions.</span>");
                        }
                        echo ("</p>");
                    }
                ?></li><?php
            }
        }
    
        ?>
    </ul>
</body></html>


