<?php

use App\Models\MdHeatNumberMirror;

echo "--- SIMULATION START ---\n";
$query = 'A214';

$results = MdHeatNumberMirror::where('status', 'active')
    ->where(function ($q) use ($query) {
        $q->where('heat_number', 'like', "%{$query}%")
            ->orWhere('item_name', 'like', "%{$query}%");
    })
    ->limit(20)
    ->get(['heat_number', 'item_code', 'item_name', 'size', 'customer', 'line']); // Matching controller exactly

echo "Count: " . $results->count() . "\n";
echo json_encode($results, JSON_PRETTY_PRINT);
echo "\n--- SIMULATION END ---\n";
