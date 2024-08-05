<?php

require_once __DIR__ . "/../../core.php";
require_once __DIR__ . "/../../lib/housekeeping.php";

echo "Removing old session IDs from match table...";
removeOldSessionIDs();

echo "Removing old audit logs...";
removeOldAuditLogs();

echo "Housekeeping done.";