<?php

use PHPUnit\Framework\TestCase;

require_once 'api.php';

class ApiTest extends TestCase
{
    private $csvFilePath = 'services.csv'; 
    
    public function testReadCSVData()
    {
        $data = readCSVData($this->csvFilePath);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }

    public function testWriteCSVData()
    {
        $data = [
            ['Ref' => '1', 'Centre' => 'A', 'Service' => 'X', 'Country' => 'UK'],
            ['Ref' => '2', 'Centre' => 'B', 'Service' => 'Y', 'Country' => 'US'],
        ];

        writeCSVData($this->csvFilePath, $data);

        $readData = readCSVData($this->csvFilePath);
        $this->assertEquals($data, $readData);
    }

    public function testAddOrUpdateService()
    {
        $data = [
            ['Ref' => '1', 'Centre' => 'A', 'Service' => 'X', 'Country' => 'UK'],
            ['Ref' => '2', 'Centre' => 'B', 'Service' => 'Y', 'Country' => 'US'],
        ];

        $newService = ['Ref' => '2', 'Centre' => 'C', 'Service' => 'Z', 'Country' => 'FR'];

        $updatedData = addOrUpdateService($data, $newService);
        $expectedData = [
            ['Ref' => '1', 'Centre' => 'A', 'Service' => 'X', 'Country' => 'UK'],
            ['Ref' => '2', 'Centre' => 'C', 'Service' => 'Z', 'Country' => 'FR'],
        ];

        $this->assertEquals($expectedData, $updatedData);
    }

    public function testAddOrUpdateServiceIntegration()
    {
        $data = [
            'Ref' => '4',
            'Centre' => 'D',
            'Service' => 'W',
            'Country' => 'DE',
        ];

        $jsonPayload = json_encode($data);

        // Make a POST request to add/update a service entry
        $ch = curl_init($this->apiBaseUrl . '/api.php');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonPayload),
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        // Assert the response status code and message
        $this->assertEquals(200, $statusCode);
        $this->assertJson($response);
        $responseData = json_decode($response, true);
        $this->assertEquals('Service entry added or updated successfully.', $responseData['message']);

        // Verify that the data was actually updated in the CSV file
        $csvData = readCSVData('services.csv');
        $this->assertIsArray($csvData);
        $found = false;
        foreach ($csvData as $entry) {
            if ($entry['Ref'] === '4' && $entry['Centre'] === 'D' && $entry['Service'] === 'W' && $entry['Country'] === 'DE') {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Service entry not found in the CSV file.');
    }
}
