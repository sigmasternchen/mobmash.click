<?php

function getFaviconUrl($left, $right) {
    $name = "/images/eggs/chimera/" .
        strtolower(str_replace(" ", "_", $left["name"] . "  " . $right["name"] . ".png"));
    if (file_exists(__DIR__ . "/../html/" . $name)) {
        return $name;
    } else {
        return "/images/eggs/spawn_egg.png";
    }
}