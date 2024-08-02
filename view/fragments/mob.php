<?php
    $side ??= "";
    $mob ??= [];
    $csrfToken ??= "";
?>
<div class="mob" onclick="htmx.trigger(document.forms['<?= $side ?>'], 'submit', {})" id="form-<?= $side ?>">
    <form action="?<?= $side ?>" method="POST" name="<?= $side ?>"
          data-hx-post="?<?= $side ?>&ajax" data-hx-target=".selection" data-hx-swap="outerHTML"
          data-hx-ext="img-preload" data-preload-spinner=".middle">
        <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
        <h2><?= $mob["name"]; ?></h2>
        <img alt="<?= $mob["name"]; ?>" src="/images/mobs/<?= $mob["image"] ?? "_placeholder.png"; ?>">
        <script>setTimeout(() => { document.getElementById("form-<?= $side ?>").style.cursor = "pointer"; }, 1);</script>
        <noscript><input class="fallback" type="submit" value="<?= $mob["name"]; ?>"></noscript>
    </form>
</div>
