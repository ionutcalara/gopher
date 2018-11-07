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

# Commands

## Podio

> podio:create

You can create any item under an application in podio provided you have info regarding the id's required. The easiest way to start is to lookup the data on their (api browser)[https://developers.podio.com/doc/items/get-item-22360] and use that structure to formulate the request.
Example:

>errand?api_token=logSzecj3uT3FZ9KoPQqUqD4dbgLAmZ1gOzplIHf6UdLriWgQq6G5I5Urljy&command=podio:create&application=MEETINGS&params=text::headline::"New%20test%20with%20applications"$:contact::attendees::190070207$$191829119$:date::time::"2018-11-23%2012:00:00"$$"2018-11-29%2013:00:00"$:app::clients::531841335$$531841341`

### Env

You will need to set the env to include your podio client id and secret as well as the podio application id and token in the following format:
`PODIO_APPLICATION_REFERENCE_ID` `PODIO_APPLICATION_REFERENCE_TOKEN`.
You can then call the command and set the name of the application (APPLICATION_REFERENCE) in the url, and that would map the call to your desired application.

### Params

The params are split by `&:` and each param has 3 parts split by `::`. The first part is type of the field: `text|date|contact|app|`'.
The second param is the external id of the field.
The third param is the value of the field. It can contain multiple values separated by `$$`
