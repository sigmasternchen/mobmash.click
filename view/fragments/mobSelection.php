<div class="selection">
<?php
    $mob = $left ?? [];
    $side = "left";
    include __DIR__ . "/mob.php";

?>
    <div class="separator">
        <div class="new-pairing">
            <a href="?new&csrfToken=<?= $csrfToken ?? "" ?>" onclick="return false;"
               data-hx-get="?ajax&new&csrfToken=<?= $csrfToken ?? "" ?>" data-hx-target=".selection" data-hx-swap="outerHTML"
               data-hx-ext="img-preload" data-loading-callback="startSpinner()" data-loaded-callback="stopSpinner()"
            >
                New Pairing <i class="fa fa-rotate-right"></i>
            </a>
        </div>
    </div>
<?php

    $mob = $right ?? [];
    $side = "right";
    include __DIR__ . "/mob.php";
?>
</div>
