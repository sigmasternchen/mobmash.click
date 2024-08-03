<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $title ?? ""; ?></title>
        <link rel="stylesheet" type="text/css" href="/styles/main.css" />
        <link rel="stylesheet" type="text/css" href="/styles/fonts.css" />
        <link rel="stylesheet" type="text/css" href="/fonts/fontawesome/css/font-awesome.css" />
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
