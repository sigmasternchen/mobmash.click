<?php

require_once __DIR__ . "/request.php";
require_once __DIR__ . "/database.php";

function handleWikiRequest(string $args, bool $rvslots = true): array {
    $url = "https://minecraft.wiki/api.php?action=query&format=json&" . $args;

    if ($rvslots) {
        $url .= "&rvslots=main";
    }

    $response = get($url);
    $response = json_decode($response, true);

    if ($rvslots) {
        $content = getWikiTextFromResponse($response);
        if (str_contains($content, "#REDIRECT")) {
            $matches = [];
            preg_match("/\[\[([^#\]]+)[^]]*]]/", $content, $matches);
            $response = get(preg_replace("/titles=[^&]*/", "titles=" . $matches[1], $url));
            $response = json_decode($response, true);
        }
    }

    return $response;
}

function getWikiTextFromResponse(array $response): string {
    return array_values($response["query"]["pages"])[0]["revisions"][0]["slots"]["main"]["*"];
}

function getImageUrlFromResponse(array $response): string {
    return array_values($response["query"]["pages"])[0]["imageinfo"][0]["url"];
}

function getMobs(): array {
    $wikiPage = handleWikiRequest("titles=Mob&prop=revisions&rvprop=content");
    $wikiText = getWikiTextFromResponse($wikiPage);

    $matches = [];
    preg_match_all('/\{\{EntityLink\|([^}]+)}}/', $wikiText, $matches);

    return array_unique($matches[1]);
}

function getImageName(string $name): string {
    $nameWithUnderscores = str_replace(" ", "_", $name);
    $wikiPage = handleWikiRequest("titles=" . $nameWithUnderscores . "&prop=revisions&rvprop=content");
    $wikiText = getWikiTextFromResponse($wikiPage);

    $matches = [];
    preg_match("/(image[0-9]*|1-1)\s*=\s*([^\n]+)\n/", $wikiText, $matches);

    $imageName = $matches[2] ?? "";

    if (str_contains($imageName, ".")) {
        return $imageName;
    }

    preg_match("/" . preg_quote($name) . "[^.]*\.(gif|png|jpg|jpeg)/", $wikiText, $matches);

    return $matches[0] ?? "";
}

function getImageUrlFromName(string $filename): string {
    $filename = str_replace(" ", "_", $filename);
    $response = handleWikiRequest("titles=File:" . $filename . "&prop=imageinfo&iiprop=url", false);
    return getImageUrlFromResponse($response);
}

function getImage(string $name): string {
    $name = getImageName($name);
    if ($name == "") {
        return "";
    } else {
        return getImageUrlFromName($name);
    }
}

function downloadImage(string $url, string $mobname): string {
    $name = preg_replace(
        "/[^.]*(\.[a-zA-Z0-9]+).*/",
        strtolower(str_replace(" ", "_", $mobname)) . "$1",
        basename($url)
    );

    $file = fopen(__DIR__ . "/../html/images/mobs/" . $name, "w");

    $response = get($url);

    fwrite($file, $response);
    fclose($file);

    return $name;
}

function addOrUpdateMob(string $name, string $filename) {
    global $pdo;

    $query = $pdo->prepare("SELECT name from mm_mobs where name = ?");
    $query->execute([$name]) or die("unable to check if mob exists");
    if ($query->rowCount() == 0) {
        $query = $pdo->prepare("INSERT INTO mm_mobs (name, image) VALUES (?, ?)");
        $query->execute([$name, $filename]) or die("unable to add new mob");
        echo "      added\n";
    } else {
        $query = $pdo->prepare("UPDATE mm_mobs SET image = ? WHERE name = ?");
        $query->execute([$filename, $name]) or die("unable to update mob");
        echo "      updated\n";
    }
}