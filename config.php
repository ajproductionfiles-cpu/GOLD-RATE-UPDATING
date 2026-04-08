<?php

declare(strict_types=1);

const DB_PATH = __DIR__ . '/storage/portal.sqlite';
const UPLOAD_DIR = __DIR__ . '/public/uploads';
const APP_URL = 'http://localhost:8000';
const MAX_UPLOAD_BYTES = 5 * 1024 * 1024;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
