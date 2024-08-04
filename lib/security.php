<?php

function makeCcrfToken(): string {
    return bin2hex(random_bytes(8));
}

const AUDIT_EVENT_SESSION_CREATED = "session_created";
const AUDIT_EVENT_MATCH_ADDED = "match_added";

function auditLog(string $event, string $session, string|null $details) {
    global $pdo;
    $query = $pdo->prepare("INSERT INTO mm_audit_log (event, session, details) VALUES (?, ?, ?)");
    $query->execute([$event, $session, $details]);
}

function ensureSession() {
    session_start();
    if (!isset($_SESSION["exists"])) {
        $_SESSION["exists"] = true;
        auditLog(AUDIT_EVENT_SESSION_CREATED, session_id(), null);
    }
}