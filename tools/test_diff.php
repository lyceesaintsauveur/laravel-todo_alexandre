<?php

require __DIR__ . '/../vendor/autoload.php';
use Carbon\Carbon;

$now = Carbon::parse('2025-11-19')->startOfDay();
$d1 = Carbon::parse('2025-11-18')->startOfDay();
$d2 = Carbon::parse('2025-11-19')->startOfDay();
$d3 = Carbon::parse('2025-11-29')->startOfDay();
echo 'past: ' . $d1->diffInDays($now, false) . PHP_EOL;
echo 'today: ' . $d2->diffInDays($now, false) . PHP_EOL;
echo 'future: ' . $d3->diffInDays($now, false) . PHP_EOL;
