<?php
    $mob ??= [];
    $trends ??= [];
    // we need to have a request unique suffix for the trend canvas id
    // - otherwise Chart.js will get confused
    $uniqueSuffix = intval(microtime(true) * 1000) % 1000000;
?>
<tr>
    <td class="right">
        <?= $mob["position"] ?>
    </td>
    <td>
        <img alt="<?= $mob["name"] ?>" src="/images/mobs/<?= $mob["image"] ?>" />
    </td>
    <td>
        <a target="_blank" href="https://minecraft.wiki/w/<?= str_replace(" ", "_", $mob["name"]) ?>">
            <?= $mob["name"] ?>
        </a>
    </td>
    <td class="hide-on-mobile right">
        <?= number_format($mob["rating"]) ?>
    </td>
    <td class="hide-on-mobile right">
        <?= $mob["matches"] ?>
    </td>
    <td class="hide-on-mobile right">
        <?= $mob["wins"] ?>
    </td>
    <td class="hide-on-mobile right">
        <?= $mob["losses"] ?>
    </td>
    <td>
        <div class="trend-container">
            <canvas id="trend-<?= $mob["id"] . $uniqueSuffix ?>"></canvas>
        </div>
        <script>
            (function() {
                const dates = JSON.parse('<?=
                    json_encode(array_map(fn($datapoint) => $datapoint["date"], $trends[$mob["id"]]))
                ?>');
                const ratings = JSON.parse('<?=
                    json_encode(array_map(fn($datapoint) => doubleval($datapoint["rating"]), $trends[$mob["id"]]))
                ?>');
                new Chart("trend-<?= $mob["id"] . $uniqueSuffix ?>", {
                    type: "line",
                    data: {
                        labels: dates,
                        datasets: [
                            {
                                fill: false,
                                data: ratings,
                            }
                        ]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false,
                            },
                        },
                        maintainAspectRatio: false,
                        animation: false,
                    }
                });
            })();
        </script>
        <noscript>Trend graphs need JavaScript, unfortunately. : (</noscript>
    </td>
</tr>
