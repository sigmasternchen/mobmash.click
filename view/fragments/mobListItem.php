<?php
    $mob ??= [];
    $trends ??= [];
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
    <td>
        <canvas id="trend-<?= $mob["id"] ?>"></canvas>
        <script>
            (function() {
                const dates = JSON.parse('<?=
                    json_encode(array_map(fn($datapoint) => $datapoint["date"], $trends[$mob["id"]]))
                ?>');
                const ratings = JSON.parse('<?=
                    json_encode(array_map(fn($datapoint) => doubleval($datapoint["rating"]), $trends[$mob["id"]]))
                ?>');
                new Chart("trend-<?= $mob["id"] ?>", {
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
                        aspectRatio: 5,
                        animation: false,
                    }
                });
            })();
        </script>
        <noscript>Trend graphs need JavaScript, unfortunately. : (</noscript>
    </td>
</tr>
