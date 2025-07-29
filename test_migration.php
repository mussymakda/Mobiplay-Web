<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

$columns = Schema::getColumnListing('users');

echo "Users table columns:\n";
foreach ($columns as $column) {
    echo "- $column\n";
}

$newFields = ['phone_number', 'address_line1', 'address_line2', 'city', 'state_province', 'postal_code', 'country'];

echo "\nChecking new fields:\n";
foreach ($newFields as $field) {
    echo "- $field: " . (in_array($field, $columns) ? 'EXISTS' : 'MISSING') . "\n";
}
