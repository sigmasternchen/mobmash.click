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
        <nav data-hx-boost="true">
            <ul class="left">
                <li><a href="/">MobMash</a></li>
                <li><a href="/results">Results</a></li>
            </ul>
            <ul class="right">
                <li><a href="/about">About</a></li>
                <li><a href="https://github.com/overflowerror/mobmash.click">Source</a></li>
                <li><a href="/privacy">Privacy</a></li>
            </ul>
        </nav>
        <?php
            if (isset($content)) {
                $content();
            }
        ?>
    </body>
</html>
