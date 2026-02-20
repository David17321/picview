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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Viewer</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">   

    <link rel="stylesheet" type="text/css" href="picview.css">

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"
        async>
    </script>   
    
    <script
        src="https://code.jquery.com/jquery-4.0.0.min.js"
        integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao="
        crossorigin="anonymous">
    </script>

</head>

<body>
    <div class="container">
        <img id="mainimg" style="width: 200px; height: auto; margin: 10%;" src="favicon.ico">
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
