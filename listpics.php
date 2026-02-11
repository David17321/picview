<?php
// To Do:  Use Masonry JS Library to arrange pictures.
$devVersion = true;


if ($devVersion) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

$maindir = $_GET['dir'] ?? '.';
if ($maindir === false || !is_dir($maindir)) {
    echo "Invalid directory.";
    die();
}
$imageDirs = glob($maindir . '/*', GLOB_ONLYDIR);

$allImages = [];
foreach ($imageDirs as $imageDir) {
    $allImages[] = getImages($imageDir);  
}

function imWidth($a, $b) {
    $sizeA = getimagesize($a);
    $sizeB = getimagesize($b);
    if ($sizeA && $sizeB) {
        return ($sizeA[0]) <=> ($sizeB[0] );
    }
    return 0;
}   

function getImages($directory) {
    $globString = $directory . "/*.{jpg,jpeg,JPG JPEG}";
    $imageFiles = glob($globString, GLOB_BRACE);
    usort($imageFiles, "imWidth");
    return $imageFiles;
}

///////////////////////////////////////////////////////////////////////////////
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Browser</title>
    
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" 
        crossorigin="anonymous">


    <link rel="stylesheet" type="text/css" href="picview.css">

    <script
        src="https://code.jquery.com/jquery-4.0.0.min.js"
        integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao="
        crossorigin="anonymous"
        async>
    </script>

    <script
        src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js">
    </script>

</head>
<body>
    <h1>Image Browser<?php if($devVersion) echo("  <em>[DEV VERSION&mdash;DO NOT UPLOAD]</em>"); ?></h1>
    <?php
    echo("<ul>");
    for ($i = 0; $i < sizeof($imageDirs); $i++) {
        echo("<li>\n");
        echo("<span>". $imageDirs[$i] . "</span>\n");
        echo("<details>\n");
        foreach($allImages[$i] as $img) {  
            $imSize =  getimagesize($img);
            echo("<p><span>" . $img . "</span><span>" . $imSize[0] . "&times;" . $imSize[1] . "</span></p>\n");
        }
        echo("</details>\n");
        echo("</li>\n");
    } 
    ?>
</body>
</html>
