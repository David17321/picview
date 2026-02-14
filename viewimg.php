<?php

$fileName = $_GET['file'];
if (!isImage($fileName)) {
    echo("not an image");
    die();
}

function isImage($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }
    // Ensure file is large enough to contain a signature
    if (filesize($filePath) < 12) {
        return false;
    }
    $type = exif_imagetype($filePath);
    return $type !== false;
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
        crossorigin="anonymous"
        async>
    </script>

    <style>
        .main_image {
            display: flex;
            height:100vh;
            width:100vw;
            justify-content: center;
            align-items: center;
        }            
        .main_image img {
            display: block;
            max-width:100%;
            max-height:100%;
        }
    </style>

</head>
<body>
    <div class="main_image">
        <?php echo("<img src=\"" . $fileName . "\"> "); ?>
    </div>
</body>
</html>
