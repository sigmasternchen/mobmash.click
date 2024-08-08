<!DOCTYPE html>
<?php
    function makeNavigationLink(string $name, string $url, bool $newTab = false): void {
        $currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/");
        $targetPath = rtrim(parse_url($url, PHP_URL_PATH), "/");
        $targetHost = parse_url($_SERVER['REQUEST_URI'], PHP_URL_HOST);
        $active = false;
        if ($targetHost === NULL && $currentPath === $targetPath) {
            $active = true;
        }
        ?><li class="menu-item <?= $active ? "active" : "" ?>"><a <?= $newTab ? 'target="_blank"' : '' ?> href="<?= $url ?>"><?= $name ?></a></li><?php
    }
?>
<html lang="en">
    <head>
        <title><?= $title ?? "" ?></title>
        <meta name="description" content="<?= $description ?? "" ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/styles/main.css" />
        <link rel="stylesheet" type="text/css" href="/styles/fonts.css" />
        <link rel="stylesheet" type="text/css" href="/styles/emoji.css" />
        <link rel="stylesheet" type="text/css" href="/fonts/fontawesome/css/font-awesome.css" />
        <script type="application/javascript" src="/js/bundle.js"></script>
    </head>
    <body>
        <nav data-hx-boost="true">
            <div class="hamburger">
                <i class="fa fa-bars"></i>
                <input type="checkbox" class="checkbox" />
            </div>
            <ul class="left">
                <?php
                    makeNavigationLink("MobMash", "/");
                    makeNavigationLink("Result", "/results");
                ?>
            </ul>
            <ul class="right">
                <?php
                    makeNavigationLink("About", "/about");
                    makeNavigationLink("Source", "https://github.com/overflowerror/mobmash.click", true);
                    makeNavigationLink("Privacy", "/privacy");
                ?>
            </ul>
        </nav>
        <div id="content">
            <?php
                if (isset($content)) {
                    $content();
                }
            ?>
        </div>
        <div id="disclaimer">
            <p>
                This is not an official Minecraft website. We are not associated with Mojang or Microsoft.<br>
                Minecraft content and materials on this site are trademarks and copyrights of <a href="https://www.minecraft.net/">Mojang Studios</a>.
            </p>
        </div>
        <div id="version">v<?= VERSION ?></div>
    </body>
</html>
