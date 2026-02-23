<?php
include "imagedirinfo.php";

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
