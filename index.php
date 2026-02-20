<?php
    //include 'enable_warnings.php';

    $maindir = ".";
    $directories = glob($maindir . '/*', GLOB_ONLYDIR);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Browser</title>
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
        crossorigin="anonymous"
        async>
    </script>
   
</head>
<body>
    <div class=container>
        <h1>Picture Gallery</h1>
        <h2>Make your selection below</h2>
        <ul class="dirlist">
        <?php
        foreach ($directories as $dir) {
            echo("<li><a href=\"showdirimages.php?dir=" . $dir . 
                "\" class=\"abc\">" . basename($dir) . "</a></li>");
        }
        ?>
        </ul>
    </div>
</body>
</html>
