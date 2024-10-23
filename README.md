# Simple-Commerce Api

Example project containting key components for creating api's with Laravel11

## Project setup

1- Clone this repo to your local machine

`git clone https://github.com/amrelsayed/simple-commerce.git`

2- You can run the project localy by two ways either via Docker or by `php artisan serve` if you used the latest you have to make sure that composer, php, and redis are installed in your machine. If you choosed Docker only you have to have Docker installed

I'll assume you have Docker installed

3- run this command `./vendor/bin/sail up` to run containers

4- run `./vendor/bin/sail composer install`

5- run `copy .env.example .env`

6- run `./vendor/bin/sail artisan key:generate`

7- run `./vendor/bin/sail artisan migrate --seed`

7- you can run unit tests via this command `./vendor/bin/sail artisan test`

## Api's

we have the following api's

**Products List**

This is a public api all users can access it without authentication

Example:

`curl --location --request GET 'http://localhost/api/products?name=cu&category_id=1&price_from=20&price_to=150' \
--header 'Content-Type: application/json' \
--data-raw '{
  "user_id": 1,
  "products": [
    { "id": 1, "quantity": 2 },
    { "id": 3, "quantity": 1 }
  ]
}
'`

**Login**

Example:

`curl --location --request POST 'http://localhost/api/login' \
--form 'email="test@example.com"' \
--form 'password="password"'`

**Create Order**

Example:

`curl --location --request POST 'http://localhost/api/orders' \
--header 'Authorization: Bearer 2|sFr6xGvtr8kxCekY7Ne8RGlAguoM01XGartY1j5w75699478' \
--header 'Content-Type: application/json' \
--data-raw '{
  "user_id": 1,
  "products": [
    { "id": 1, "quantity": 2 },
    { "id": 3, "quantity": 1 }
  ]
}
'`

**Order Details**

Example

`curl --location --request GET 'http://localhost/api/orders/1' \
--header 'Authorization: Bearer 2|sFr6xGvtr8kxCekY7Ne8RGlAguoM01XGartY1j5w75699478' \
--header 'Content-Type: application/json' \
--data-raw '
{"data": {
        "id": 1,
        "total_amount": 1579,
        "products": [
            {
                "id": 1,
                "name": "et",
                "price": 666,
                "stock": 51
            },
            {
                "id": 3,
                "name": "eos",
                "price": 247,
                "stock": 47
            }
        ]
    }
}'`
