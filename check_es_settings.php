<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Log;

try {
    $client = ClientBuilder::create()
        ->setHosts([config('services.elasticsearch.host', 'http://145.223.98.97:9201')])
        ->build();

    $indexName = 'pages';

    echo "Checking index: $indexName\n";

    if (!$client->indices()->exists(['index' => $indexName])) {
        echo "❌ Index '$indexName' does NOT exist!\n";
        exit(1);
    }

    $settings = $client->indices()->getSettings(['index' => $indexName]);
    $mapping = $client->indices()->getMapping(['index' => $indexName]);

    echo "✅ Index exists.\n\n";

    // Check Analysis Settings
    $analysis = $settings[$indexName]['settings']['index']['analysis'] ?? null;
    if ($analysis) {
        echo "🔍 Analysis Settings Found:\n";
        print_r($analysis);
        
        if (isset($analysis['analyzer']['arabic_analyzer'])) {
            echo "✅ 'arabic_analyzer' is defined.\n";
            $filters = $analysis['analyzer']['arabic_analyzer']['filter'] ?? [];
            if (in_array('arabic_normalization', $filters)) {
                echo "✅ 'arabic_normalization' filter is used in arabic_analyzer.\n";
            } else {
                echo "❌ 'arabic_normalization' filter is MISSING in arabic_analyzer!\n";
            }
        } else {
            echo "❌ 'arabic_analyzer' is NOT defined!\n";
        }
    } else {
        echo "❌ No Analysis settings found (might be using defaults)!\n";
    }

    echo "\n----------------------------------------\n";

    // Check Mapping
    $props = $mapping[$indexName]['mappings']['properties'] ?? [];
    if (isset($props['content'])) {
        echo "🔍 Content Field Mapping:\n";
        print_r($props['content']);
        
        if (($props['content']['analyzer'] ?? '') === 'arabic_analyzer') {
            echo "✅ Content field uses 'arabic_analyzer'.\n";
        } else {
            echo "❌ Content field uses '" . ($props['content']['analyzer'] ?? 'standard') . "' instead of 'arabic_analyzer'.\n";
        }
    } else {
        echo "❌ Content field not found in mapping!\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
