<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function renderHeader(string $title, ?array $user): void
{
    $portalName = htmlspecialchars(setting('portal_name', 'Jewellery Portal'));
    echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>' . htmlspecialchars($title) . ' - ' . $portalName . '</title>';
    echo '<link rel="stylesheet" href="styles.css"></head><body>';
    echo '<header><strong>' . $portalName . '</strong><nav>';
    if ($user) {
        echo '<a href="dashboard.php">Dashboard</a><a href="gallery.php">Gallery</a><a href="search.php">Search Similar</a>';
        if ($user['role'] === 'admin') {
            echo '<a href="categories.php">Categories</a><a href="settings.php">Settings</a>';
        }
        echo '<a href="logout.php">Logout</a>';
    }
    echo '</nav></header><div class="container">';

    $flash = pullFlash();
    if ($flash) {
        echo '<div class="flash ' . htmlspecialchars($flash['type']) . '">' . htmlspecialchars($flash['message']) . '</div>';
    }
}

function renderFooter(): void
{
    echo '</div></body></html>';
}
