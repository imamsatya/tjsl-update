<?php

function renderStatusBadge($status) {
    $classMapping = [
        'Finish' => 'badge-light-success',
        'In Progress' => 'badge-light-primary',
        'Unfilled' => 'badge-light-warning',
    ];

    if (array_key_exists($status, $classMapping)) {
        $class = $classMapping[$status];
        return "<span class='btn badge {$class} fw-bolder me-auto px-4 py-3'>{$status}</span>";
    }

    return '';
}