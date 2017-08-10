# Installation

1. Clone this repository
2. Create and configure `.env` file based on `.env.example`.
3. Install the dependencies and start the server.

```sh
$ composer install
$ php artisan key:generate
$ php artisan service:install
$ php artisan serve
```

# API List

| API | URL |
| ------ | ------ |
| Register | /api/register |
| Login | /api/login |
| Logout | /api/logout |
| Create new order | /api/orders/ |
| Submit payment proof | /api/orders/submit-proof |
| Retrieve my orders | /api/orders/ |
| Get my order detail (check status) | /api/orders/{id} |
| Check shipping status | /api/shippings/status |
| **Admin API** |  |
| Retrieve all orders | /admin/orders |
| Get order detail | /admin/orders/{id} |
| Change order status | /admin/orders/change-status |

# Command List

- Create new admin `php artisan user:new-admin [name] [email] [password]`
