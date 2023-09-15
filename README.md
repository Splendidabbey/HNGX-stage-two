# PHP REST API with MySQL

This is a simple PHP REST API that allows CRUD operations on a "person" resource, using MySQL as the database. It provides endpoints for creating, reading, updating, and deleting person records. The API is designed to handle dynamic parameters for filtering by name and includes validation and error handling to ensure data integrity and security.

## Getting Started

Follow these instructions to set up and run the API on your local machine.

### Prerequisites

- PHP (>= 7.0)
- MySQL server
- Web server (e.g., Apache)
- Composer (for installing dependencies)

### Installation

1. Clone this repository to your local machine.

git clone https://github.com/yourusername/php-rest-api.git
cd php-rest-api



2. Install XAMPP or any similar Web server Apllication, then start Apache and Mysql


3. Create a MySQL database and import the schema from `database.sql`.

4. Configure the database connection in `config.php`.

### Usage

Start your web server (e.g., Apache).

Make API requests using tools like Postman or cURL:

- Create a new person:

POST /api
{
"name": "John Doe",
"age": 30,
"email": "johndoe@example.com"
}



- Get details of a person:

GET /api/1



- Update a person's details:

PUT /api/1
{
"name": "Updated Name",
"age": 35
}



- Delete a person:

DELETE /api/1



### API Documentation

For detailed API documentation, please refer to [DOCUMENTATION.md](DOCUMENTATION.md).

## Testing

You can run automated tests to ensure the API's functionality.

1. Install PHPUnit globally (if not already installed):

composer global require phpunit/phpunit



2. Run the tests:

phpunit tests



## Known Limitations

- This API currently supports basic CRUD operations and does not include advanced features like authentication or pagination.

## Contributing

Feel free to open issues and pull requests to contribute to this project. Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.