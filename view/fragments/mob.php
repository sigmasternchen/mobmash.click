<?php
    $side ??= "";
    $mob ??= [];
    $csrfToken ??= "";
?>
<form action="?<?= $side ?>" method="POST" name="<?= $side ?>" id="form-<?= $side ?>">
    <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
    <div class="mob" onclick="document.forms['<?= $side ?>'].submit()">
        <h2><?= $mob["name"]; ?></h2>
        <img alt="<?= $mob["name"]; ?>" src="/images/mobs/<?= $mob["image"] ?? "_placeholder.png"; ?>">
    </div>
</form>