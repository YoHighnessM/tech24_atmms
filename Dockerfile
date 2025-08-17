# Use an official PHP image with a web server
# The "apache" tag includes an Apache web server, which is good for simple PHP apps
FROM php:8.2-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy all the application files from your project into the container
COPY . .

# Expose port 80 to the outside world
EXPOSE 80