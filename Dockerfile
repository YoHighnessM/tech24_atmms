# Use an official PHP image with Apache
FROM php:8.2-apache

# Install the mysqli PHP extension
RUN docker-php-ext-install mysqli

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy all the application files from your project into the container
COPY . .

# Expose port 80 to the outside world
EXPOSE 80