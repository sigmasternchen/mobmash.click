<div class="selection">
<?php
    $mob = $left ?? [];
    $side = "left";
    include __DIR__ . "/mob.php";

?>
    <div class="separator">
    </div>
<?php

    $mob = $right ?? [];
    $side = "right";
    include __DIR__ . "/mob.php";
?>
</div>
