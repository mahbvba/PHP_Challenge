# Instruct PHP Developer Code Challenge (v1.0)

Instruct-ERIC provides scientific services to researchers around the world, and this is made available
to people through a Service Catalogue. This catalogue allows a researcher to find out what services are
available to them in a given country, and which research centre provides it.

## The Task

In this directory you'll see a file `services.csv`, which contains a small sample of our service catalogue.

1. Create a PHP API endpoint to expose this information via JSON.
2. This API should have a GET endpoint that can query the service catalogue based on COUNTRY CODE
3. The API should have a POST endpoint to add new entries or update existing entries (based on reference code)

You do not need to worry about user security or access controls on the data at this stage, but you should 
consider issues of data sanitisation and normalisation.

4. You should create a `Dockerfile` and a `docker-compose.yml` file to containerise your service.

### Extra credit

 * Write a PHP CLI (command line interface) tool to query and update the service catalogue 
 * Include an OpenAPI Specification (https://github.com/OAI/OpenAPI-Specification) for your API 

## How much time do I have?

We expect this to take no longer than a couple of hours, but feel free to take less or more time as you
see fit. 

## Our expectations

We expect readable, commented and maintainable code as a result. You should think of this as being
a merge request for a production system, so your code should be readily understandable by another
developer - we value readability over cleverness!

Think about how you can demonstrate your code works correctly, producing the results expected, and will
remain functional as it is developed on by other developers. What tests can you add, or tools can you use?

You can use whatever tools you are familiar with, and can use any libraries you feel necessary, however
the final submission must be developed in PHP 7 or above.

We will evaluate your submission on:

 * Correctness
 * Completeness
 * Quality

## Deliverables

 * Please provide an invite link and code to a PRIVATE GitHub or GitLab repository containing your code
 * This repo should include instructions for running the software
 * Include any config-as-code configuration scripts, or other resources (e.g. docker files), as necessary
 * Do not submit any binaries

Good Luck!
