<div class="selection">
    <?php
        $mob = $left ?? [];
        include __DIR__ . "/mob.php";
    ?>
    <div class="separator">
        OR
    </div>
    <?php
        $mob = $right ?? [];
        include __DIR__ . "/mob.php";
    ?>
</div>
