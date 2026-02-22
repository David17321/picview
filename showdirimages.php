<?php

//include 'enable_warnings.php';

$maindir = $_GET['dir'] ?? '.';
if ($maindir === false || !is_dir($maindir)) {
    echo "Invalid directory.";
    die();
}

class ImageDirInfo {
    public $dirName;
    public $aspectRatio;
    public $files;
    function __construct($dirName) {
        $this->dirName = $dirName;
        $globString = $this->dirName . "/*.{jpg,jpeg,JPG JPEG}";
        $this->files = glob($globString, GLOB_BRACE);
        usort($this->files, "imWidth");
        if (sizeof($this->files) == 0) {
            $this->aspectRatio = 0;
        } else {
            $s = getImageSize($this->files[0]);
            $this->aspectRatio = $s[0] / $s[1];
        }
    }

    function containsFiles() {
        return (sizeof($this->files) > 0);
    }

    function printInfo() {
        echo($this->dirName . "&nbsp;&nbsp;");
        echo($this->aspectRatio . "<br>");
    }

    function getThumbnail() {
        return $this->files[0];
    }

    function getLargest() {
        return $this->files[sizeof($this->files)-1];
    }
} // End of class ImageDirInfo definition

$imageDirs = glob($maindir . '/*', GLOB_ONLYDIR);
$imageDirObjects = array();
foreach ($imageDirs as $imageDir) {
    $idr = new ImageDirInfo($imageDir);
    if ($idr->containsFiles()) {
        $imageDirObjects[] = $idr;
    }
}

$txtFiles = glob($maindir . '/*.txt');
if (!$txtFiles) {
    $headingText = "Image Gallery";
} else {
    $headingText = file_get_contents($txtFiles[0] );
}

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
    <title><?php echo("$headingText"); ?></title>  
    <link rel="stylesheet" type="text/css" href="showdirimages.css">
    <?php include "head-contents.php"; ?>
 
    <script
        src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js">
    </script>

    <script
        src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js" async>
    </script>

    <script async>
        $('.grid').masonry({
            itemSelector: '.grid-item',
            columnWidth: '.grid-sizer',
            percentPosition: true
        })       
    </script>

</head>
<body>
    <div id="main_container">
    <?php
    echo("<h1>" . $headingText . "</h1>"); 
    if(isset($devVersion)) echo("<h1><em>[DEV VERSION&mdash;DO NOT UPLOAD]</em></h1>"); 
    echo("<div class=\"grid\">");
    echo("<div class=\"grid-sizer\"></div>");
    shuffle($imageDirObjects);
    for ($i = 0; $i < sizeof($imageDirObjects); $i++){
        $ar = $imageDirObjects[$i]->aspectRatio;

        // Allocate grid space depending on the image's aspect ratio
        if ($ar > 2.0) {
             $shapeClass =  "grid-item--width3 grid-item--height1";
        }

        if ($ar <= 2.0 && $ar > 1.1) {
            $shapeClass =  "grid-item--width2 grid-item--height1";
        }

        if ($ar <= 1.1 && $ar > 0.9) {
            $shapeClass =  "grid-item--width1 grid-item--height1";
        }

        if ($ar <= 0.9 ) {
            $shapeClass =  "grid-item--width1 grid-item--height2";
        }     

        echo("<div class=\"grid-item" . 
            " " . $shapeClass . 
            "\"><a href=\"" . 
            "viewimg.php?file=" .
            ($imageDirObjects[$i]->getLargest()) .
            "\"> <img src=\"" . 
            $imageDirObjects[$i]->getThumbnail() .
             "\" title=\"Select to view full size.\"" . 
             "></a></div>");
    }
    echo("</div>");
?>
</div>
</body>
</html>
