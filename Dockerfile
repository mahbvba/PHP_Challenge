# Use an official PHP runtime as the base image
FROM php:7.4-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the PHP script and CSV file into the container
COPY api.php services.csv ./

# Enable Apache modules and rewrite
RUN a2enmod rewrite

# Expose port 80 for the Apache web server
EXPOSE 80

# Start Apache when the container starts
CMD ["apache2-foreground"]