<?php

$devVersion = true;

if ($devVersion) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

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
            
            animation-name: fadeOut;
            animation-duration: 5s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
        
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
</body>
</html>

<!-- 
$(document).ready(function(){
  $("#container").mousemove(function(){
    // Stop ongoing animation, reset to opaque, then fade out
    $(".text-element")
      .stop(true, true)
      .css("opacity", 1)
      .animate({opacity: 0}, 1000);
  });
});

AI Overview
To restart a text opacity animation on mouse move using jQuery, use the
.mousemove() event to trigger a chain of .stop() and .animate() functions. Calling .stop(true, true) instantly halts the current animation and skips to its end state before restarting, preventing animation queuing. 
Key Implementation Details:

    Target Element: Select the text container (e.g., $(".text-element")).
    Restart Technique: $(selector).stop(true, true).css("opacity", 1).animate({opacity: 0}, 1000);.
    Event: Use .mousemove(function(){...}) on the container or document. 

-->


<!--

<div id="clickme">
  Click here
</div>
<img id="book" src="book.png" alt="" width="100" height="123">
// With the element initially shown, we can dim it slowly:
$( "#clickme" ).on( "click", function() {
  $( "#book" ).fadeTo( "slow" , 0.5, function() {
    // Animation complete.
  });
});
-->
