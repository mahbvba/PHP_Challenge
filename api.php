<?php
// Specify the CSV file path
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

// Function to get services for a specific country code
function getServiceByCountryCode($data, $countryCode) {
    $services = array();
    foreach ($data as &$service) {
        if (strtolower($service['Country']) === strtolower($countryCode)) {
            array_push($services, $service);
        }
    } 
    return $services;
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
        $data[] = $newService; 
    }
    return $data;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Read the existing data from the CSV file
        $existingData = readCSVData($csvFilePath);

        // Get the JSON data from the POST request body
        $postData = file_get_contents('php://input');
        $newService = json_decode($postData, true);

        // Validate the JSON data
        if ($newService !== null && is_array($newService)) {
            // Add or update the service entry
            $updatedData = addOrUpdateService($existingData, $newService);

            // Write the updated data back to the CSV file
            writeCSVData($csvFilePath, $updatedData);

            // Respond with a success message
            header('HTTP/1.1 200 OK');
            echo json_encode(['message' => 'Service entry added or updated successfully.'], JSON_PRETTY_PRINT);
        } else {
            // Invalid JSON data
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid JSON data.'], JSON_PRETTY_PRINT);
        }
        break;
    case 'GET':
        // Read the existing data from the CSV file
        $existingData = readCSVData($csvFilePath);

        $url = $_SERVER['REQUEST_URI'];
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        $countryCode = $params['country'];
        $serviceData = getServiceByCountryCode($existingData, $countryCode);
        echo json_encode(['data' => $serviceData], JSON_PRETTY_PRINT); 
        break;
    default:
        // Invalid request method
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(['error' => 'Method not allowed.'], JSON_PRETTY_PRINT);
        break;
}

?>
