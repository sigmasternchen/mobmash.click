<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $title ?? ""; ?></title>
        <link rel="stylesheet" type="text/css" href="/styles/main.css" />
        <script type="application/javascript" src="/js/bundle.js"></script>
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
