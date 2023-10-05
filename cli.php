<?php
$csvFilePath = 'services.csv';

// Function to read CSV data and return it as an associative array
function readCSVData($filePath) {
    $data = [];
    if (($handle = fopen($filePath, 'r')) !== false) {
        while (($row = fgetcsv($handle)) !== false) {
            $serviceData = array_combine(['Ref', 'Centre', 'Service', 'Country'], $row);
            $data[] = $serviceData;
        }
        fclose($handle);
    }
    return $data;
}

// Function to write CSV data from an associative array
function writeCSVData($filePath, $data) {
    if (($handle = fopen($filePath, 'w')) !== false) {
        foreach ($data as $service) {
            fputcsv($handle, array_values($service));
        }
        fclose($handle);
    }
}

// Function to query services by country code
function queryServicesByCountry($data, $countryCode) {
    $result = [];
    foreach ($data as $service) {
        if (strcasecmp($service['Country'], $countryCode) === 0) {
            $result[] = $service;
        }
    }
    return $result;
}

// Function to add or update a service entry
function addOrUpdateService($data, $newService) {
    $updated = false;
    foreach ($data as &$service) {
        if ($service['Ref'] === $newService['Ref']) {
            $service = $newService; // Update existing entry
            $updated = true;
            break;
        }
    }
    if (!$updated) {
        $data[] = $newService; // Add new entry if not updated
    }
    return $data;
}

// Check command line arguments
if ($argc < 2) {
    echo "Usage: php cli.php [action] [parameters]\n";
    echo "Actions:\n";
    echo "  query [countryCode]: Query services by country code\n";
    echo "  add [Ref] [Centre] [Service] [Country]: Add or update a service entry\n";
    exit(1);
}

$action = $argv[1];

// Read the existing data from the CSV file
$existingData = readCSVData($csvFilePath);

if ($action === 'query' && $argc === 3) {
    // Query action
    $countryCode = strtoupper($argv[2]);
    $result = queryServicesByCountry($existingData, $countryCode);
    foreach ($result as $service) {
        echo json_encode($service) . "\n";
    }
} elseif ($action === 'add' && $argc === 6) {
    // Add action
    $newService = [
        'Ref' => $argv[2],
        'Centre' => $argv[3],
        'Service' => $argv[4],
        'Country' => $argv[5]
    ];
    $updatedData = addOrUpdateService($existingData, $newService);
    writeCSVData($csvFilePath, $updatedData);
    echo "Service entry added or updated successfully.\n";
} else {
    echo "Invalid action or parameters. Use 'php cli.php' for usage instructions.\n";
    exit(1);
}
?>
