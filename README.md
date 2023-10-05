#  PHP CLI and API catalogue

The project allows you to query services by country code, as well as add or update service entries.

## Getting Started

### Prerequisites

Before you begin, ensure you have met the following requirements:

- [PHP](https://php.net/) (7.4 or higher)
- [Composer](https://getcomposer.org/)
- [Docker](https://www.docker.com/) (if you plan to use the provided `docker-compose.yml` for local development)

### Installation

 Install project dependencies using Composer:

   ```bash
   composer install
   ```

## Usage

### CLI

The CLI script (`cli.php`) provides two main commands:

- Query services by country code:

   ```bash
   php cli.php query UK
   ```

- Add or update a service entry:

   ```bash
   php cli.php add 4 D W DE
   ```

### API

The RESTful API is accessible at `http://localhost:8080` (you can customize the port in `docker-compose.yml`). It provides the following endpoints:

You can run the PHP server by running the following:

`docker compose up`

This will build and run the docker container which will run our PHP server.

You will then be able to interact with the REST API.

#### GET Endpoint

- `GET /api.php/services?country={countryCode}`: Query services by country code.

In order to use the GET endpoint to create a new entry, use your REST client of choice

Make a GET request to the following endpoint: 

http://localhost:8080/api.php/services?country=uk

This will return data on all services with the country code uk.

#### POST Endpoint

- `POST /api.php/services/{ref}`: Add or update a service entry by reference code.

In order to use the POST endpoint to create a new entry, use your REST client of choice

Create a new entry

Send a POST request to the following endpoint: “http://localhost:8080/api.php/services (http://localhost:8080/api.php/services)/ (http://localhost:8080/api.php/services/)“

The body must be in JSON format, see the following example:

```
{
    "Ref": "SOMEREF123",
    "Centre": "Cool new centre",
    "Service": "Time Travel",
    "Country": "UK"
}
```

This will create a new entry with the details entered.

Update an existing entry

Send a POST request to the following endpoint: “http://localhost:8080/api.php/services (http://localhost:8080/api.php/services)/ (http://localhost:8080/api.php/services/)“

The body must be in JSON format, see the following example:

We will be using the 
Ref of an existing entry so we can update its values


```
{
    "Ref": "APPLAB1",
    "Centre": "Aperture Science",
    "Service": "Portal Technology",
    "Country": "UK"
}
```

For API documentation, see `openapi.yml`.

## Running Tests

### PHPUnit Tests

PHPUnit tests are available to ensure the functionality of both the CLI and API components.

#### API Tests

API tests are located in the `tests/ApiTests.php` file and include tests for reading and writing CSV data, as well as adding or updating services.

To run API tests:

```bash
vendor/bin/phpunit tests/ApiTests.php
```

#### CLI Tests

CLI tests are located in the `tests/CliTests.php` file and include tests for reading and writing CSV data, querying services by country, and adding or updating services.

To run CLI tests:

```bash
vendor/bin/phpunit tests/CliTests.php
```

