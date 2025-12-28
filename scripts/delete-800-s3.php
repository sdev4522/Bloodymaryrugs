<?php
// scripts/delete-800-s3.php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

$disk = Storage::disk(config('filesystems.default') === 's3' ? 's3' : config('filesystems.cloud', 's3'));

// optional: set prefix if you want to limit scan
$prefix = ''; // e.g. 'custom-1/'

echo "Scanning S3 for files ending with '800x800.jpg' under prefix '{$prefix}'...\n";

$allFiles = $disk->allFiles($prefix);
$matches = [];
foreach ($allFiles as $f) {
    if (str_ends_with($f, '800x800.jpg')) {
        $matches[] = $f;
    }
}

$count = count($matches);
echo "Found {$count} matching file(s).\n";

// save report
$report = storage_path('logs/delete-800-report-'.date('Ymd_His').'.log');
file_put_contents($report, implode(PHP_EOL, $matches));
echo "Report saved to: {$report}\n";

if ($count === 0) {
    exit(0);
}

echo "LIST (first 50 shown):\n";
foreach (array_slice($matches, 0, 50) as $m) {
    echo $m . PHP_EOL;
}

echo "\nDRY RUN complete. To actually delete, re-run with DELETE=1 environment variable.\n";
echo "Example: php scripts/delete-800-s3.php DELETE=1\n";

if (getenv('DELETE') == '1') {
    // confirm
    echo "Deleting {$count} files. Type YES to confirm: ";
    $handle = fopen ("php://stdin","r");
    $line = trim(fgets($handle));
    if ($line !== 'YES') {
        echo "Aborted.\n";
        exit(1);
    }

    $deleted = 0;
    foreach ($matches as $m) {
        try {
            if ($disk->delete($m)) {
                echo "Deleted: $m\n";
                $deleted++;
            } else {
                echo "Failed to delete: $m\n";
            }
        } catch (\Throwable $e) {
            echo "Error deleting $m : ".$e->getMessage()."\n";
        }
    }
    echo "Done. Deleted {$deleted}/{$count}.\n";
}
