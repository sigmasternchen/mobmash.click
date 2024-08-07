<?php

require_once __DIR__ . "/database.php";

// variable because heredoc doesn't support consts
$MIGRATION_TABLE = "mm_migrations";

function ensureMigrationsTable(): void {
    global $pdo;
    global $MIGRATION_TABLE;

    if ($pdo->query(<<<EOF
            SELECT tablename FROM pg_tables 
            WHERE schemaname = 'public' AND tablename = '{$MIGRATION_TABLE}'
        EOF
    )->rowCount() != 0) {
        return;
    }

    $pdo->exec(<<<EOF
        CREATE TABLE IF NOT EXISTS $MIGRATION_TABLE (
            id INT NOT NULL,
            file VARCHAR(255) NOT NULL,
            applied TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        );
    EOF
    ) !== false or die("unable to initialize migrations: " . $pdo->errorCode());
}

function getAllMigrations(): array {
    $files = scandir(__DIR__ . "/../migrations/");
    $files = array_values(array_filter($files, fn($f) => $f[0] != "."));
    sort($files);

    $migrations = [];

    foreach ($files as $file) {
        $delimiterPos = strpos($file, "_");
        $migrations[intval(substr($file, 0, $delimiterPos))] = $file;
    }

    return $migrations;
}

function getAppliedMigrations(): array {
    global $pdo;
    global $MIGRATION_TABLE;

    $result = $pdo->query(<<<EOF
        SELECT * FROM {$MIGRATION_TABLE}
    EOF);

    $migrations = [];
    foreach ($result->fetchAll() as $row) {
        $migrations[$row["id"]] = $row["file"];
    }

    return $migrations;
}

function getMigrationsToApply(): array {
    global $pdo;

    $all = getAllMigrations();
    $applied = getAppliedMigrations();

    foreach ($applied as $id => $file) {
        unset($all[$id]);
    }

    return $all;
}

function executeSqlScript(string $sql, string $file) {
    global $pdo;

    if ($pdo->exec($sql) === false) {
        die("failed to apply migration " . $file . ": " . $pdo->errorCode());
    }
}

function applyMigration(int $id, string $file) {
    global $pdo;
    global $MIGRATION_TABLE;

    $pdo->beginTransaction();

    $sql = file_get_contents(__DIR__ . "/../migrations/" . $file);
    if (!$sql) {
        die("Unable to read migration file: " . $file);
    }

    executeSqlScript($sql, $file);

    $statement = $pdo->prepare(<<<EOF
        INSERT INTO {$MIGRATION_TABLE}
            (id, file) VALUES 
            (?, ?)
    EOF);
    $statement->execute([$id, $file]);

    try {
        $pdo->commit();
    } catch (PDOException $e) {
        // this might happen if the migration script contains a DDL statement
        // -> ignore
    }
}

function migrate() {
    ensureMigrationsTable();

    $migrations = getMigrationsToApply();
    foreach ($migrations as $id => $file) {
        applyMigration($id, $file);
    }
}