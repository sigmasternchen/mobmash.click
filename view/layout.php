<!DOCTYPE html>
<html>
    <head>
        <title><?= $title ?? ""; ?></title>
        <link rel="stylesheet" type="text/css" href="/styles/main.css" />
    </head>
    <body>
        <nav>
        </nav>
        <?php
            if (isset($content)) {
                $content();
            }
        ?>
    </body>
</html>
