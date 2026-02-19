<?php

include 'enable_warnings.php';

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

    <style>
        .main_image {
            display: flex;
            height:100vh;
            width:100vw;
            justify-content: center;
            align-items: center;
            position: fixed;
            background-color: #00000000;
        }  

        .main_image img {
            display: block;
            max-width:100%;
            max-height:100%;
            z-index: -1;
        }

        @keyframes fadeOut {
            from {opacity: 1;}
            to {opacity: 0;}
        }

        #clickme {
            height:100vh;
            width:100vw;
            position: fixed;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 300%;
            text-shadow: 2px 2px 2px black;
            
            animation-name: fadeOut;
            animation-duration: 5s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;  
        }

        @media screen and (width < 600px) {
            p {
                visibility: hidden;
            }
        }
    </style>

</head>
<body>
    <div class="main_image">
        <?php echo("<img src=\"" . $fileName . "\" title=\"" . $imgText . "\"> "); ?>
    </div>

    <div id="clickme">
        <p id="message"><?php echo("$imgText");?></p>
    </div>

    <script>
        $(document).ready(function(){
            $("#clickme").on("mousemove", function(){
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
        });
    </script>
</body>
</html>

