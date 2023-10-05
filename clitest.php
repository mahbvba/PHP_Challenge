<?php

use PHPUnit\Framework\TestCase;

require_once 'cli.php';

class CliTest extends TestCase
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

    public function testQueryServicesByCountry()
    {
        $data = [
            ['Ref' => '1', 'Centre' => 'A', 'Service' => 'X', 'Country' => 'UK'],
            ['Ref' => '2', 'Centre' => 'B', 'Service' => 'Y', 'Country' => 'US'],
            ['Ref' => '3', 'Centre' => 'C', 'Service' => 'Z', 'Country' => 'UK'],
        ];

        $countryCode = 'UK';
        $result = queryServicesByCountry($data, $countryCode);
        $expectedResult = [
            ['Ref' => '1', 'Centre' => 'A', 'Service' => 'X', 'Country' => 'UK'],
            ['Ref' => '3', 'Centre' => 'C', 'Service' => 'Z', 'Country' => 'UK'],
        ];

        $this->assertEquals($expectedResult, $result);
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
    public function testQueryServicesByCountryFunctional()
    {
        // Simulate running the CLI command for querying services by country
        $output = shell_exec('php cli.php query UK');

        // Assert the output of the command
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('Service entries for UK:', $output);
        $this->assertStringContainsString('Ref: 1, Centre: A, Service: X, Country: UK', $output);
    }

    public function testAddOrUpdateServiceFunctional()
    {
        // Simulate running the CLI command for adding/updating a service entry
        $output = shell_exec('php cli.php add 4 D W DE');

        // Assert the output of the command
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('Service entry added or updated successfully.', $output);

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
    public function testCliIntegration()
    {
        // Simulate running the CLI command for querying services by country
        $output = shell_exec('php cli.php query UK');

        // Assert the output of the command
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('Service entries for UK:', $output);
        $this->assertStringContainsString('Ref: 1, Centre: A, Service: X, Country: UK', $output);

        // Simulate running the CLI command for adding/updating a service entry
        $output = shell_exec('php cli.php add 4 D W DE');

        // Assert the output of the command
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('Service entry added or updated successfully.', $output);

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