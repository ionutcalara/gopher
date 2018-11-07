# gopher
Laravel based application to run certain jobs when called remotely

# Getting started

* Clone the repository to the folder set for the application
* Install composer
>`composer install`
* Update the env file with your application information
* Run the migrations and seed
>`php artisan migrate --seed`

# Calls

* The api gets authenticated via a token you will be able to find in the users table under the api_token column. Just apend that to the query as follows
>`your.url/api/v1/errand?api_token=API_TOKEN&command=commandgoeshere&param1=data1&--optionalparam=data2`
* `command` is any artisan command allowed by the application
* all the other params respect the [artisan documentation](https://laravel.com/docs/5.7/artisan)

# Testing

* Testing the application will require setup of a test database. You need to create .env.testing and add API_TOKEN variable, based on the TestUsersSeeder. Make sure you are using a test environment database as it is wiped clean for every test.