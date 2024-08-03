<?php
    $mob ??= [];
?>
<tr>
    <td>
        <?= $mob["position"] ?>
    </td>
    <td>
        <img src="/images/mobs/<?= $mob["image"] ?>" />
    </td>
    <td>
        <?= $mob["name"] ?>
    </td>
    <td>
        <?= number_format($mob["rating"]) ?>
    </td>
    <td>
        <?= $mob["matches"] ?>
    </td>
    <td>
        <?= $mob["wins"] ?>
    </td>
    <td>
        <?= $mob["losses"] ?>
    </td>
</tr>
