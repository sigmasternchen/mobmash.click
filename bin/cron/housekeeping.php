<?php

require_once __DIR__ . "/../../core.php";
require_once __DIR__ . "/../../lib/housekeeping.php";

echo "Removing old session IDs from match table...\n";
removeOldSessionIDs();

echo "Removing old audit logs...\n";
removeOldAuditLogs();

echo "Housekeeping done.\n";