<?php

 $maindirs = glob('./*', GLOB_ONLYDIR);
 $thumbnails = [];
 foreach($maindirs as $dir) {
    $gallerydirs = glob($dir . '/*', GLOB_ONLYDIR);
    foreach($gallerydirs as $imagedir) {
        $imageInfo = new ImageDirInfo($imagedir);
        if($imageInfo->containsFiles()) {
            $thumb = $imageInfo->getThumbnail();
            $thumbnails[] = $thumb;
        }
    }
 }

 $json_thumbs = json_encode($thumbnails);

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

function imWidth($a, $b) {
    $sizeA = getimagesize($a);
    $sizeB = getimagesize($b);
    if ($sizeA && $sizeB) {
        return ($sizeA[0]) <=> ($sizeB[0] );
    }
    return 0;
}   
?>

<!doctype html>
<html lang="en">
<head>
    <title>Image Viewer</title>
    <?php include "head-contents.php"; ?>
</head>

<body>
    <div class="container">
        <h1>Picture Gallery</h1>
    </div>
    <div class="container selection_area">
        <div>
            <h2>Make your selection below</h2>
            <ul class="dirlist">
            <?php
            foreach ($maindirs as $dir) {
                echo("<li><a href=\"showdirimages.php?dir=" . $dir . "\" >" .
                 basename($dir) . "</a></li>");
            }
            ?>
            </ul>
        </div>
        <div>
            <img id="mainimg" src="favicon.ico" style="width: 300px;">
        </div>
    </div>

    <script>
        var thumbnailsArray = <?php echo $json_thumbs; ?>;
        
        function displayRandomImage() {
            if (thumbnailsArray.length > 0) {
                var randomIndex = Math.floor(Math.random() * thumbnailsArray.length);
                document.getElementById('mainimg').src = thumbnailsArray[randomIndex];
            }
        }
        
        // Display a random image immediately and then every 5 seconds
        displayRandomImage();
        setInterval(displayRandomImage, 5000);
    </script>
 
</body>
</html>
