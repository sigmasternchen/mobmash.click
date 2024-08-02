<?php
    $side ??= "";
    $mob ??= [];
    $csrfToken ??= "";
?>
<div class="mob" onclick="htmx.trigger(document.forms['<?= $side ?>'], 'submit', {})" id="form-<?= $side ?>">
    <form action="?<?= $side ?>" method="POST" name="<?= $side ?>"
          hx-post="?<?= $side ?>&ajax" hx-target=".selection" hx-swap="outerHTML"
          hx-ext="img-preload" data-preload-spinner=".middle">
        <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
        <h2><?= $mob["name"]; ?></h2>
        <img alt="<?= $mob["name"]; ?>" src="/images/mobs/<?= $mob["image"] ?? "_placeholder.png"; ?>">
    </form>
</div>
