<?php

function removeOldAuditLogs() {
    global $pdo;
    $pdo->exec("DELETE FROM mm_audit_log WHERE time < (CURRENT_TIMESTAMP - INTERVAL '6 months')");
}


function removeOldSessionIDs() {
    global $pdo;
    $pdo->exec(<<<EOF
        UPDATE mm_matches 
        SET session = NULL 
        WHERE 
            created < (CURRENT_TIMESTAMP - INTERVAL '6 months')
            AND session IS NOT NULL
    EOF
    );
}