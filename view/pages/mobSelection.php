<div class="choice">
    <h1>
        Which do you like better?
    </h1>
    <?php
        $separator = !($ajax ?? false);

        require_once __DIR__ . "/../fragments/mobSelection.php";
    ?>
    <div class="middle">
        <div class="spinner">
            <img src="/images/gras.png" alt="spinner" />
        </div>
        <div class="or">
            OR
        </div>
    </div>
</div>
