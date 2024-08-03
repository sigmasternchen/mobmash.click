<!DOCTYPE html>
<?php
    function makeNavigationLink(string $name, string $url) {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $targetPath = parse_url($url, PHP_URL_PATH);
        $targetHost = parse_url($_SERVER['REQUEST_URI'], PHP_URL_HOST);
        $active = false;
        if ($targetHost === NULL && $currentPath === $targetPath) {
            $active = true;
        }
        ?>
            <li class="menu-item <?= $active ? "active" : "" ?>"><a href="<?= $url ?>"><?= $name ?></a></li>
        <?php
    }
?>
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
                <?php makeNavigationLink("MobMash", "/"); ?>
                <?php makeNavigationLink("Result", "/results"); ?>
            </ul>
            <ul class="right">
                <?php makeNavigationLink("About", "/about"); ?>
                <?php makeNavigationLink("Source", "https://github.com/overflowerror/mobmash.click"); ?>
                <?php makeNavigationLink("Privacy", "/privacy"); ?>
            </ul>
        </nav>
        <?php
            if (isset($content)) {
                $content();
            }
        ?>
    </body>
</html>
