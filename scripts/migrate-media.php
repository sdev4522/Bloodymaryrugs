<?php

use Illuminate\Support\Facades\Storage;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Botble media folder usually:
$local = Storage::disk('public'); // /storage/app/public
$s3 = Storage::disk('s3');

$files = $local->allFiles();

foreach ($files as $file) {
    echo "Uploading: $file\n";
    $s3->put($file, $local->get($file), 'private');
}

echo "\nMigration complete.\n";
