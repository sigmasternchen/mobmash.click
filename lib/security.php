<?php

function makeCcrfToken(): string {
    return bin2hex(random_bytes(8));
}
