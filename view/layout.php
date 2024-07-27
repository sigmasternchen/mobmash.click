<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title ?? ""; ?></title>
        <link rel="stylesheet" type="text/css" href="/styles/main.css" />
    </head>
    <body>
        <?php
            if (isset($content)) {
                $content();
            }
        ?>
    </body>
</html>
