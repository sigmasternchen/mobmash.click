<?php
$mobs ??= [];
?>
<table class="results-list">
    <thead>
        <tr>
            <th>
                Position
            </th>
            <th>
                <!-- image -->
            </th>
            <th>
                Name
            </th>
            <th>
                Rating
            </th>
            <th>
                Matches
            </th>
            <th>
                Wins
            </th>
            <th>
                Losses
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
