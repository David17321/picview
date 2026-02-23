<?php

//include 'enable_warnings.php';

$fileName = $_GET['file'];

if (!(isImage($fileName))) {
    echo ("[ " . $fileName . "]  is not a proper image file.");
    die();
}

$dirPath = dirname($fileName);

$htmlFiles = glob($dirPath . '/*.txt');

if ($htmlFiles) {
    $imgText = file_get_contents($htmlFiles[0]);
} else {
    $imgText = "";
}

function isImage($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }

    // Ensure file is large enough to contain a signature
    if (filesize($filePath) < 12) {
        return false;
    }
    return true;

    $imSizeInfo = list($width, $height, $type, $attr) = getimagesize( $fileName );
    return ($imSizeInfo === true);
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php include "head-contents.php"; ?>
    <link rel="stylesheet" type="text/css" href="viewimg.css">
    <title>Image Viewer</title>
</head>

<body>
    <div class="main_image">
        <?php echo("<img src=\"" . $fileName . "\" title=\"" . $imgText . "\"> "); ?>
    </div>

    <div id="textarea">
        <p id="message"><?php echo("$imgText");?></p>
    </div>

    <script>
        $(document).ready(function(){
            const linkToOpen = "<?php echo $fileName; ?>";
            
            $("#textarea").on("mousemove", function(){
                // Stop the current animation, reset opacity, and restart the animation
                $(this)
                    .stop(true, true)
                    .css({
                        "opacity": 1,
                        "animation": "none"
                    })
                    .offset(); // Trigger reflow to apply the changes
                
                // Restart the animation
                $(this).css("animation", "fadeOut 5s forwards");
            });
            
            $("#textarea").on("click", function(){
                window.location.href = linkToOpen;
            });
        });
    </script>
</body>
</html>
