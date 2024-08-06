<?php
$mobs ??= [];

function makeSortButton(string $field): void {
    global $orderColumn, $orderDirection;

    $icon = "fa-sort";
    $direction = "desc";

    if ($field == $orderColumn) {
        if ($orderDirection == "desc") {
            $direction = "asc";
            $icon = "fa-sort-down";
        } else {
            $icon = "fa-sort-up";
        }
    }

    ?>
        <a href="?order=<?= $field ?>&direction=<?= $direction ?>" onclick="return false;"
           data-hx-get="?ajax&order=<?= $field ?>&direction=<?= $direction ?>"
           data-hx-target=".results-list" data-hx-swap="outerHTML"
        >
            <i class="fa <?= $icon ?>"></i>
        </a>
    <?php
}

?>
<table class="results-list">
    <thead>
        <tr>
            <th>
                Pos. <?php makeSortButton("position"); ?>
            </th>
            <th>
                <!-- image -->
            </th>
            <th>
                Name <?php makeSortButton("name"); ?>
            </th>
            <th class="hide-on-mobile">
                Rating <?php makeSortButton("rating"); ?>
            </th>
            <th class="hide-on-mobile">
                Matches <?php makeSortButton("matches"); ?>
            </th>
            <th class="hide-on-mobile">
                Wins <?php makeSortButton("wins"); ?>
            </th>
            <th class="hide-on-mobile">
                Losses <?php makeSortButton("losses"); ?>
            </th>
            <th>
                Trend
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($mobs as $mob) {
                require __DIR__ . '/mobListItem.php';
            }
        ?>
    </tbody>
</table>
