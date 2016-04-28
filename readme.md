# Laravel API

A simple API built in Laravel.

## Prerequisites

You must have `composer` installed. For further information, visit the official composer page:

https://getcomposer.org/doc/00-intro.md#globally

For running the tests, you must also have `npm` and `gulp` installed. You can visit their respective documentations for instructions on how to get them installed.

## Installation

1. Clone the repository into a folder on your computer:

    ```sh
    $ git clone https://github.com/spspasov/be-a-simple-api.git
    $ cd be-a-simple-api
    ```

2. Create a MySQL database with a name of your choosing.
3. Enter the required details for the database in the .env file located in the root of the project.
4. Run composer to install all the dependecies:

    ```sh
    $ composer install
    ```

    4.1 You might need to run the migrations as well:

    ```sh
    $ php artisan migrate
    ```

5. Run a local server from the root of the project by running:

    ```sh
    $ php artisan serve
    ```

6. Visit http://localhost:8000/

## Running the tests

1. Install npm dependencies:

    ```sh
    $ npm install
    ```

2. Create a second database for the tests. You must edit the `config/database.php` file and provide credentials for the `mysql_testing` database driver. It's easiest if you just create a database with a name  like `database_name_testing` and use the same credentials you used for your main database.
3. Run gulp in *test* mode:

    ```sh
    $ gulp tdd
    ```