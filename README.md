# About Larvify RMS

This is a Restaurant Management System for Restaurants all over the world. Build with Laravel 11.x and ❤️

## Installation

In terms of local development, you can use the following requirements:

- PHP 8.3 - with SQLite or MySql, and other common extensions.

If you have these requirements, you can start by cloning the repository and installing the dependencies:

```bash
git clone https://github.com/Larvify/RMS-API.git

cd RMS-API
```

## Control your version

Create a new git branch with your name

```php
git branch 'new_branch_name'

git checkout 'new_branch_name'
```

OR, It will create a new `branch` and `checkout` to it.

```
git checkout -b 'new_branch_name'
```

Next, install the dependencies using [Composer](https://getcomposer.org):

```bash
composer install
```

After that, set up your `.env` file:

```bash
cp .env.example .env

php artisan key:generate
```

## Prepare your database and run the migrations:

### For sqlite

```bash
touch database/database.sqlite
```

### For Mysql

Create a `database` in your PhpMyAdmin and update the `.env` variables

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Then, Run

```bash
php artisan migrate --seed
```

OR run, if you already have some tables and data in your database. It will remove all you previous data and insert new

```bash
php artisan migrate:fresh --seed 
```

Finally, start the development server:

```bash
php artisan serve
```

# API Doc

## Authentication

### Resgister

This should be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/register
```

Example body in json format like:

```json
{
    "name": "Test",
    "email": "test@gmail.com", // email shoud be uinque
    "password": "password",
    "password_confirmation": "password",
    "phone": "123456789",
    "user_type": "customer"
}
```

You will get a response in `json` format like:

```json
{
    "status": "success",
    "access_token": "2|mZ5ToVgGP9yvc5W2JXNBqpr65vM7cpZ6dX4Ivt6Fa8636112",
    "message": "User registration successful."
}
```

NOTE: Collect and save the `access_token` to pass auth check

### Login

Login must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/login
```

Example body in `json` format like:

```json
{
    "email": "test@gmail.com",
    "password": "password"
}
```

You will get a response in `json` format like:

```json
{
    "status": "success",
    "message": "Login successful.",
    "access_token": "2|81I6miirhSlLnV0AqkFPVvK92SoV1ZBlz6ftj1RSecaf32dc",
    "token_type": "Bearer"
}
```

NOTE: Collect and save the `access_token` to pass auth check

### Logout

Logout must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/logout
```

You will get a response in `json` format like:

```json
{
    "status": "success",
    "message": "Logged out successfully."
}
```

You must have logged in else you will get a response like:

```json
{
    "message": "Unauthenticated."
}
```

## User Management

### Index : Users List [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/users
```

You will get a response in `json` with users collection like:

```json
{
    "rows": [
        {
            "id": 100,
            "name": "Domenick Weber",
            "email": "retha.larson@example.org",
            "email_verified_at": "2024-06-02T00:49:37.000000Z",
            "phone": "818.540.4842",
            "user_type": "admin",
            "photo": null,
            "father_name": "Dr. Nat Wolf Jr.",
            "mother_name": "Tia Carroll",
            "address": "198 Beer Mews Apt. 128\nOndrickaton, MI 92433-2890",
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T00:49:37.000000Z"
        },
        {
            "id": 98,
            "name": "Katelynn Bernier",
            "email": "dayton40@example.org",
            "email_verified_at": "2024-06-02T00:49:37.000000Z",
            "phone": "820-764-8977",
            "user_type": "admin",
            "photo": null,
            "father_name": "Mr. Jamey Keebler II",
            "mother_name": "Romaine Mraz",
            "address": "242 Yundt Prairie\nSouth Charleneside, NM 11427-5159",
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T00:49:37.000000Z"
        }
    ]
}
```

### Create a User  [ Auth required ]

This must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/users
```

Example body in `json` format like:

```json
{
    "name": "Domenick Weber",
    "email": "larson@example.org", // email must be unique
    "password": "password",
    "cpassword": "password",
    "phone": "818.540.4842"
}
```

You will get a response in `json` format like:

```json
{
    "message": "User created successfully"
}
```

### Show a User [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/users/1
```

You will get a response in `json` format like:

```json
{
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@gmail.com",
        "email_verified_at": null,
        "phone": "012578557878",
        "user_type": "admin",
        "photo": null,
        "father_name": null,
        "mother_name": null,
        "address": null,
        "created_at": "2024-06-02T00:49:37.000000Z",
        "updated_at": "2024-06-02T00:49:37.000000Z"
    }
}
```

### Update a User data [ Auth required ]

This must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/users/1
```

Example body in `json` format like:

```json
{
    "name": "Domenick Weber",
    "email": "larson@example.org",
    "phone": "818.540.4842"
}
```

You will get a response in `json` format like:

```json
{
    "message": "User update successfully"
}
```

### Delete a User [ Auth required ]

This must be a `DELETE` request to this `api` endpoints

```php
{{base_url}}/api/users/1
```

You will get a response in `json` format like:

```json
{
    "message": "User delete successfully"
}
```

## Reservation Management

### Index : Reservation List [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/reservations
```

You will get a response in `json` with users collection like:

```json
{
    "data": [
        {
            "id": 1,
            "reserved_by": {
                "id": 29,
                "name": "Dr. Camren Boyer",
                "email": "brandyn.schiller@example.com",
                "email_verified_at": "2024-06-02T00:49:37.000000Z",
                "phone": "1-360-271-5968",
                "user_type": "supplier",
                "photo": null,
                "father_name": "Prof. Oliver Tremblay",
                "mother_name": "Ayana Huels I",
                "address": "39105 Roselyn Tunnel\nHowardburgh, RI 19011-3138",
                "created_at": "2024-06-02T00:49:37.000000Z",
                "updated_at": "2024-06-02T00:49:37.000000Z"
            },
            "table": {
                "id": 1,
                "table_name": "Rose",
                "table_number": 3,
                "seats": 7,
                "status": "reserved",
                "created_at": "2024-06-02T00:49:37.000000Z",
                "updated_at": "2024-06-02T00:49:37.000000Z"
            },
            "status": "open",
            "date": "2024-05-31",
            "time": null,
            "num_guest": 7
        },
        {
            "id": 2,
            "reserved_by": {
                "id": 79,
                "name": "Prof. Jacey Stokes",
                "email": "christiansen.zechariah@example.net",
                "email_verified_at": "2024-06-02T00:49:37.000000Z",
                "phone": "870-731-9957",
                "user_type": "customer",
                "photo": null,
                "father_name": "Dr. Edwardo Kautzer",
                "mother_name": "Mrs. Teresa Swift",
                "address": "94199 Fisher Wells Apt. 960\nKenyaberg, MI 43988",
                "created_at": "2024-06-02T00:49:37.000000Z",
                "updated_at": "2024-06-02T00:49:37.000000Z"
            },
            "table": {
                "id": 7,
                "table_name": "Rose",
                "table_number": 1,
                "seats": 2,
                "status": "available",
                "created_at": "2024-06-02T00:49:37.000000Z",
                "updated_at": "2024-06-02T00:49:37.000000Z"
            },
            "status": "hold",
            "date": "2024-06-03",
            "time": null,
            "num_guest": 4
        }
    ]
}
```

### Create a Reservation [ Auth required ]

This must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/reservations
```

Example body in `json` format like:

```json
{
    "id": 1,
    "user_id": 29,
    "table_id": 1,
    "status": "open",
    "date": "2024-08-31",
    "start_time": "9:00",
    "num_guest": 7
}
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 104,
        "reserved_by": {
            "id": 29,
            "name": "Dr. Camren Boyer",
            "email": "brandyn.schiller@example.com",
            "email_verified_at": "2024-06-02T00:49:37.000000Z",
            "phone": "1-360-271-5968",
            "user_type": "supplier",
            "photo": null,
            "father_name": "Prof. Oliver Tremblay",
            "mother_name": "Ayana Huels I",
            "address": "39105 Roselyn Tunnel\nHowardburgh, RI 19011-3138",
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T00:49:37.000000Z"
        },
        "table": {
            "id": 1,
            "table_name": "Rose",
            "table_number": 3,
            "seats": 7,
            "status": "reserved",
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T00:49:37.000000Z"
        },
        "status": "open",
        "date": "2024-08-31",
        "time": null,
        "num_guest": 7
    },
    "message": "Reservation Created Successfully.",
    "status": 201
}
```

### Show a Reservation [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/reservations/1
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 1,
        "reserved_by": {
            "id": 29,
            "name": "Dr. Camren Boyer",
            "email": "brandyn.schiller@example.com",
            "email_verified_at": "2024-06-02T00:49:37.000000Z",
            "phone": "1-360-271-5968",
            "user_type": "supplier",
            "photo": null,
            "father_name": "Prof. Oliver Tremblay",
            "mother_name": "Ayana Huels I",
            "address": "39105 Roselyn Tunnel\nHowardburgh, RI 19011-3138",
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T00:49:37.000000Z"
        },
        "table": {
            "id": 1,
            "table_name": "Rose",
            "table_number": 3,
            "seats": 7,
            "status": "reserved",
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T00:49:37.000000Z"
        },
        "status": "open",
        "date": "2024-05-31",
        "time": null,
        "num_guest": 7
    }
}
```

### Update a Reservation data [ Auth required ]

This must be a `PATCH` request to this `api` endpoints

```php
{{base_url}}/api/reservations/2
```

Example body in `json` format like:

```json
{
    "user_id": 15,
    "table_id": 3,
    "status": "hold",
    "start_time": "16:19",
    "num_guest": 7
}
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 2,
        "reserved_by": {
            "id": 15,
            "name": "Litzy Schoen",
            "email": "corine.botsford@example.net",
            "email_verified_at": "2024-06-02T00:49:37.000000Z",
            "phone": "1-240-554-0564",
            "user_type": "employee",
            "photo": null,
            "father_name": "Izaiah Smitham",
            "mother_name": "Ernestina Nicolas Sr.",
            "address": "504 Torey Plaza Apt. 509\nHamillside, MI 85281-2164",
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T00:49:37.000000Z"
        },
        "table": {
            "id": 3,
            "table_name": "Daisy",
            "table_number": 5,
            "seats": 4,
            "status": "occupied",
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T00:49:37.000000Z"
        },
        "status": "hold",
        "date": "2024-06-03",
        "time": null,
        "num_guest": 7
    },
    "message": "Reservation Updated Successfully.",
    "status": 201
}
```

### Delete a Reservation [ Auth required ]

This must be a `DELETE` request to this `api` endpoints

```php
{{base_url}}/api/reservations/1
```

You will get a response in `json` format like:

```json
{
    "data": [],
    "message": "Reservation successfully deleted.",
    "status": 200
}
```

## Waiting Customers Management

### Index : Waiting Customers List [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/reservation/waiting
```

You will get a response in `json` with users collection like:

```json
{
    "data": [
        {
            "id": 1,
            "customer": {
                "id": 89,
                "name": "Abbigail Dibbert",
                "email": "stracke.tomas@example.net",
                "email_verified_at": "2024-06-02T00:49:37.000000Z",
                "phone": "412-491-1664",
                "user_type": "supplier",
                "photo": null,
                "father_name": "Alphonso Reilly",
                "mother_name": "Caitlyn Buckridge",
                "address": "55340 Tyra Ways\nPort Kathryn, ME 17947-9378",
                "created_at": "2024-06-02T00:49:37.000000Z",
                "updated_at": "2024-06-02T00:49:37.000000Z"
            },
            "num_guest": "1"
        },
        {
            "id": 2,
            "customer": {
                "id": 22,
                "name": "Daija Mohr",
                "email": "hoeger.katarina@example.com",
                "email_verified_at": "2024-06-02T00:49:37.000000Z",
                "phone": "(567) 922-8907",
                "user_type": "customer",
                "photo": null,
                "father_name": "Dr. Austen Hodkiewicz III",
                "mother_name": "Nia Rosenbaum DDS",
                "address": "52932 Delfina Burgs Apt. 811\nNew Werner, SC 94567-8451",
                "created_at": "2024-06-02T00:49:37.000000Z",
                "updated_at": "2024-06-02T00:49:37.000000Z"
            },
            "num_guest": "3"
        }
    ]
}
```

### Create a Waiting request for Customer [ Auth required ]

This must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/reservation/waiting
```

Example body in `json` format like:

```json
{
    "user_id": 1,
    "num_guest": 4
}
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 21,
        "customer": {
            "id": 1,
            "name": "Domenic Weber",
            "email": "larsons@example.org",
            "email_verified_at": null,
            "phone": "818.540.4842",
            "user_type": "admin",
            "photo": null,
            "father_name": null,
            "mother_name": null,
            "address": null,
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T02:42:29.000000Z"
        },
        "num_guest": 4
    }
}
```

### Show a Waiting Customer [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/reservation/waiting/2
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 2,
        "customer": {
            "id": 22,
            "name": "Daija Mohr",
            "email": "hoeger.katarina@example.com",
            "email_verified_at": "2024-06-02T00:49:37.000000Z",
            "phone": "(567) 922-8907",
            "user_type": "customer",
            "photo": null,
            "father_name": "Dr. Austen Hodkiewicz III",
            "mother_name": "Nia Rosenbaum DDS",
            "address": "52932 Delfina Burgs Apt. 811\nNew Werner, SC 94567-8451",
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T00:49:37.000000Z"
        },
        "num_guest": "3"
    }
}
```

### Update a Waiting Customer data [ Auth required ]

This must be a `PATCH` request to this `api` endpoints

```php
{{base_url}}/api/reservation/waiting/5
```

Example body in `json` format like:

```json
{
    "user_id": 5,
    "num_guest": 2
}
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 5,
        "customer": {
            "id": 5,
            "name": "Simeon Nienow II",
            "email": "dfadel@example.com",
            "email_verified_at": "2024-06-02T00:49:37.000000Z",
            "phone": "660-991-3138",
            "user_type": "supplier",
            "photo": null,
            "father_name": "Sim Heidenreich",
            "mother_name": "Marielle Bernhard I",
            "address": "3285 Evans Knolls\nWest Destiny, WY 18565",
            "created_at": "2024-06-02T00:49:37.000000Z",
            "updated_at": "2024-06-02T00:49:37.000000Z"
        },
        "num_guest": 2
    }
}
```

### Delete a Waiting Customer [ Auth required ]

This must be a `DELETE` request to this `api` endpoints

```php
{{base_url}}/api/reservation/waiting/1
```

You will get a response in `json` format like:

```json
{
    "data": [],
    "message": "Waiting list successfully deleted.",
    "status": 200
}
```

## Reservation Booking

### Available Tables Display [ Auth Not required ]

This must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/available-tables
```

Example body in `json` format like:

```json
{
    "date": "2024-06-02",
    "start_time": "23:00",
    "num_guest": 7
}
```

You will get a response in `json` format like:

```json
{
    "data": [
        {
            "id": 1,
            "table_name": "Tulip",
            "table_number": 3,
            "seats": 7,
            "status": "available",
            "created_at": "2024-06-02T10:46:08.000000Z",
            "updated_at": "2024-06-02T10:46:08.000000Z"
        }
    ],
    "message": "Available table list.",
    "status": 200
}
```

### Table Booking [ Auth required ]

This must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/booking
```

Example body in `json` format like:

```json
{
    "date": "2024-06-02",
    "start_time": "23:00",
    "num_guest": 7
}
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 102,
        "reserved_by": {
            "id": 1,
            "name": "Admin",
            "email": "admin@gmail.com",
            "email_verified_at": null,
            "phone": "012578557878",
            "user_type": "admin",
            "photo": null,
            "father_name": null,
            "mother_name": null,
            "address": null,
            "created_at": "2024-06-02T10:46:08.000000Z",
            "updated_at": "2024-06-02T10:46:08.000000Z"
        },
        "table": {
            "id": 3,
            "table_name": "Tulip",
            "table_number": 3,
            "seats": 6,
            "status": "occupied",
            "created_at": "2024-06-02T10:46:08.000000Z",
            "updated_at": "2024-06-02T10:46:08.000000Z"
        },
        "status": "booked",
        "date": "2025-12-23",
        "time": null,
        "num_guest": 5
    },
    "message": "Your reservation successfully done.",
    "status": 200
}
```

## Order Management

### Index : Order List [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/order
```

You will get a response in `json` with users collection like:

```json
{
    "rows": [
        {
            "id": 12,
            "order_number": 454546,
            "reservation_id": 3,
            "customer_id": 3,
            "status": "Delivered",
            "total": 500,
            "paid_amount": 200,
            "payment_method": "Cash",
            "created_by": 1,
            "created_at": "2024-06-02T11:11:59.000000Z",
            "updated_at": "2024-06-02T11:11:59.000000Z"
        },
        {
            "id": 11,
            "order_number": 454545,
            "reservation_id": 2,
            "customer_id": 3,
            "status": "Delivered",
            "total": 500,
            "paid_amount": 200,
            "payment_method": "Cash",
            "created_by": 1,
            "created_at": "2024-06-02T11:06:46.000000Z",
            "updated_at": "2024-06-02T11:06:46.000000Z"
        }
    ]
}
```

### Create an Order [ Auth required ]

This must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/order
```

Example body in `json` format like:

```json
{
    "order_number": 454547,
    "reservation_id": 3,
    "status": "Delivered",
    "total": 500,
    "paid_amount": 200,
    "payment_method": "Cash",
    "customer_id": 3,
    "menu_item_id": 3,
    "quantity": 2,
    "unit_price": 250,
    "created_by": 1,
    "payment_status": "Paid"
}
```

You will get a response in `json` format like:

```json
{
    "message": "User created successfully"
}
```

### Update a Order data [ Auth required ]

This must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/order/1
```

Example body in `json` format like:

```json
{
    "order_number": "12345",
    "reservation_id": 1,
    "status": "Delivered",
    "customer_id": 1,
    "created_by": 1,
    "menu_item_id": 1,
    "quantity": 2,
    "unit_price": 10.5,
    "total": 21.0,
    "paid_amount": 20.0,
    "payment_method": "Cash",
    "payment_status": "Paid"
}

```

You will get a response in `json` format like:

```json
{
    "message": "Order update successfully"
}
```

### Delete an Order [ Auth required ]

This must be a `DELETE` request to this `api` endpoints

```php
{{base_url}}/api/order/2
```

You will get a response in `json` format like:

```json
{
    "message": "Order delete successfully"
}
```

### Invoice Generate [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/invoice/generate/1
```

You will get a response in `json` format like:

```json
{
    "order": {
        "id": 1,
        "order_number": 12345,
        "reservation_id": 1,
        "customer_id": 1,
        "status": "Delivered",
        "total": 21,
        "paid_amount": 20,
        "payment_method": "Cash",
        "created_by": 1,
        "created_at": "2024-06-02T10:46:08.000000Z",
        "updated_at": "2024-06-02T11:33:41.000000Z"
    },
    "orderItem": [
        {
            "id": 105,
            "order_id": 1,
            "menu_item_id": 1,
            "quantity": 2,
            "unit_price": 10.5,
            "sub_total": 21,
            "customer_id": 1,
            "created_by": 1,
            "order_number": 12345,
            "created_at": "2024-06-02T11:33:41.000000Z",
            "updated_at": "2024-06-02T11:33:41.000000Z"
        }
    ]
}
```

## Category Management 

### Index : Categories List [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/categories
```

You will get a response in `json` with users collection like:

```json
{
    "data": [
        {
            "id": 1,
            "title": "Dr.",
            "type": "menu item",
            "created_at": "2024-06-02T10:46:08.000000Z",
            "updated_at": "2024-06-02T10:46:08.000000Z"
        },
        {
            "id": 2,
            "title": "Mr.",
            "type": "menu item",
            "created_at": "2024-06-02T10:46:08.000000Z",
            "updated_at": "2024-06-02T10:46:08.000000Z"
        }
    ]
}
```

### Create a Category [ Auth required ]

This must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/categories
```

Example body in `json` format like:

```json
{
    "title": "Test Category",
    "type": "reservation"
}
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 113,
        "title": "Test Category",
        "type": "reservation",
        "created_at": "2024-06-02T12:54:58.000000Z",
        "updated_at": "2024-06-02T12:54:58.000000Z"
    }
}
```

### Show a Category [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/categories/1
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 1,
        "title": "Dr.",
        "type": "menu item",
        "created_at": "2024-06-02T10:46:08.000000Z",
        "updated_at": "2024-06-02T10:46:08.000000Z"
    }
}
```

### Delete a Category [ Auth required ]

This must be a `DELETE` request to this `api` endpoints

```php
{{base_url}}/api/categories/1
```

You will get a response in `json` format like:

```json
{
    "data": [],
    "message": "Reservation successfully deleted.",
    "status": 200
}
```

## Menu Item Management

### Index : Menu Items List [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/menu-items
```

You will get a response in `json` with users collection like:

```json
{
    "data": [
        {
            "id": 1,
            "category_id": {
                "id": 4,
                "title": "Dr.",
                "type": "menu item",
                "created_at": "2024-06-02T10:46:08.000000Z",
                "updated_at": "2024-06-02T10:46:08.000000Z"
            },
            "name": "Mrs. Tamara Wintheiser IV",
            "description": "Unde a maxime sunt voluptates. Ut aut id voluptas. Suscipit esse ut aliquam vel quos aut. Adipisci asperiores sint optio et iusto id sed. Nihil quisquam error sit aliquam.",
            "price": 217,
            "images": null,
            "is_special": 0,
            "created_at": "2024-06-02T10:46:08.000000Z",
            "updated_at": "2024-06-02T10:46:08.000000Z"
        },
        {
            "id": 2,
            "category_id": {
                "id": 9,
                "title": "Mrs.",
                "type": "menu item",
                "created_at": "2024-06-02T10:46:08.000000Z",
                "updated_at": "2024-06-02T10:46:08.000000Z"
            },
            "name": "Kianna Aufderhar",
            "description": "Quasi suscipit qui exercitationem ut eaque quia. Dignissimos nihil sit sint voluptate omnis. Est et rerum odio velit non laudantium. Voluptatem maxime ducimus ea labore.",
            "price": 139,
            "images": null,
            "is_special": 0,
            "created_at": "2024-06-02T10:46:08.000000Z",
            "updated_at": "2024-06-02T10:46:08.000000Z"
        }
    ]
}
```

### Create a Menu Item [ Auth required ]

This must be a `POST` request to this `api` endpoints

```php
{{base_url}}/api/menu-items
```

Example body in `json` format like:

```json
{
    "category_id": 13,
    "name": "Test Menu Item",
    "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
    "price": "1200"
}
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 122,
        "name": "Test Menu Item",
        "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
        "price": "1200",
        "images": null,
        "is_special": null,
        "created_at": "2024-06-02T13:10:31.000000Z",
        "updated_at": "2024-06-02T13:10:31.000000Z"
    }
}
```

### Show a Menu Item [ Auth required ]

This must be a `GET` request to this `api` endpoints

```php
{{base_url}}/api/menu-items/1
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 1,
        "category_id": {
            "id": 4,
            "title": "Dr.",
            "type": "menu item",
            "created_at": "2024-06-02T10:46:08.000000Z",
            "updated_at": "2024-06-02T10:46:08.000000Z"
        },
        "name": "Mrs. Tamara Wintheiser IV",
        "description": "Unde a maxime sunt voluptates. Ut aut id voluptas. Suscipit esse ut aliquam vel quos aut. Adipisci asperiores sint optio et iusto id sed. Nihil quisquam error sit aliquam.",
        "price": 217,
        "images": null,
        "is_special": 0,
        "created_at": "2024-06-02T10:46:08.000000Z",
        "updated_at": "2024-06-02T10:46:08.000000Z"
    }
}
```

### Update a Menu Item [ Auth required ]

This must be a `PATCH` request to this `api` endpoints

```php
{{base_url}}/api/menu-items/1
```

Example body in `json` format like:

```json
{
    "category_id": 1,
    "name": "Test",
    "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
    "price": 121,
    "is_special": 1
}
```

You will get a response in `json` format like:

```json
{
    "data": {
        "id": 1,
        "name": "Test",
        "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
        "price": 121,
        "images": null,
        "is_special": 1,
        "created_at": "2024-06-02T10:46:08.000000Z",
        "updated_at": "2024-06-02T13:13:14.000000Z"
    }
}
```

### Delete a Menu Item [ Auth required ]

This must be a `DELETE` request to this `api` endpoints

```php
{{base_url}}/api/menu-items/11
```

You will get a response in `json` format like:

```json
{
    "data": [],
    "message": "Menu Item successfully deleted.",
    "status": 200
}
```
