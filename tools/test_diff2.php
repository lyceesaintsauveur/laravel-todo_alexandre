<?php
require __DIR__ . '/../vendor/autoload.php';
use Carbon\Carbon;
$now = Carbon::parse('2025-11-19')->startOfDay();
$d1 = Carbon::parse('2025-11-18')->startOfDay();
$d2 = Carbon::parse('2025-11-19')->startOfDay();
$d3 = Carbon::parse('2025-11-29')->startOfDay();

foreach ([$d1, $d2, $d3] as $d) {
    echo "date: " . $d->toDateString() . PHP_EOL;
    echo "  date->diffInDays(now,false): " . $d->diffInDays($now,false) . PHP_EOL;
    echo "  now->diffInDays(date,false): " . $now->diffInDays($d,false) . PHP_EOL;
}
