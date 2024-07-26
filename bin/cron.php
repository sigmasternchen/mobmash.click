<?php

require_once __DIR__ . "/../core.php";
require_once __DIR__ . "/../lib/updateData.php";

echo "Loading mob list...\n";
$mobs = getMobs();

echo "Filtering invalid entries...\n";
$mobs = array_filter($mobs, fn ($mob) =>
    !str_starts_with($mob, "id=") &&
    !str_contains($mob, "Old ") &&
    $mob != "NPC" && $mob != "Agent" &&
    !str_ends_with($mob, "Ghost") &&
    $mob != "Giant" &&
    $mob != "Killer Bunny"
);

echo "Fetching image URLs...\n";
$mobs = array_map(fn ($mob) => [
    "name" => $mob,
    "image" => getImage($mob)
], $mobs);

echo "Removing duplicates...\n";
$mobs = array_reduce($mobs, function ($mobs, $mob) {
    $urls = array_map(fn ($mob) => $mob["image"], $mobs);
    if (!in_array($mob["image"], $urls)) {
        $mobs[] = $mob;
    }
    return $mobs;
}, []);

echo "Downloading images...\n";
foreach ($mobs as $mob) {
    $filename = downloadImage($mob["image"], $mob["name"]);
    $mob["filename"] = $filename;
    var_dump($mob);
}