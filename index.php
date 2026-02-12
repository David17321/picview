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
}
$imageDirs = glob($maindir . '/*', GLOB_ONLYDIR);
$imageDirObjects = array();
foreach ($imageDirs as $imageDir) {
    $imageDirObjects[] = new ImageDirInfo($imageDir);
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
        src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js" async>
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

    <style>
        * { box-sizing: border-box; }

body { font-family: sans-serif; }

/* ---- grid ---- */

.grid {
  background: #222;
  max-width: 1200px;
}

/* clearfix */
.grid:after {
  content: '';
  display: block;
  clear: both;
}

/* ---- grid-item ---- */

.grid-sizer,
.grid-item {
  width: 33%;
}

.grid-item {
  height: 120px;
  float: left;
  background: #222;
  border: 2px solid #222;
  border-color: #ccc;
}

.grid-item--width2 { width: 20%; }
.grid-item--width3 { width: 30%; }
.grid-item--width4 { width: 45%; }


.grid-item--height4 { height: 50vh; }
.grid-item--height2 { height: 25vh; }

.grid-item img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 1em;
}

    </style>

</head>
<body>
    <h1>Image Browser<?php if($devVersion) echo("  <em>[DEV VERSION&mdash;DO NOT UPLOAD]</em>"); ?></h1>
    <?php
    
    echo("<div class=grid>");
    echo("<div class=\"grid-sizer\"></div>");
    for ($i = 0; $i < sizeof($imageDirObjects); $i++){
        $ar = $imageDirObjects[$i]->aspectRatio;
        if ($ar < 1) {
            $shapeClass =  "grid-item--width2 grid-item--height4";
        } else {
            if ($ar < 2) {
                $shapeClass = "grid-item--width3 grid-item--height2";
            } else {
                $shapeClass = "grid-item--width4 grid-item--height2";
            }
        }
        echo("<div class=\"grid-item" . " " . $shapeClass . "\"><img src=\"" . $imageDirObjects[$i]->getThumbnail() . "\"> </div>");
    }
    echo("</div>");
?>
</body>
</html>
