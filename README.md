🌐 **English** | [Русский](README.ru.md)

# Laravel Product Catalog API

## Take-home assignment: a Laravel 12 REST API for a product catalog with arbitrary properties (options) and filtering by them.

Build an API backend with Laravel. Database: MySQL or PostgreSQL. Expected time: 4 hours. The result must be published on GitHub.

*(Translated from the original Russian assignment — see [README.ru.md](README.ru.md) for the verbatim original.)*

### Required functionality:
- Implement a "product catalog". A product has: name, price, quantity. Product properties (options) have a name.
- Product properties must be arbitrary, i.e. configurable in the database.
- Implement catalog filtering with multiple selection, e.g. `GET /products?properties[property1][]=value1&properties[property1][]=value2&properties[property2][]=value1`.
- Provide a GET endpoint for the product catalog, paginated by 40.
- Filter products by their option values — e.g. products like "desk lamp" have options such as shade color, frame color, brand; filter the catalog by those options.

The project runs on SQLite by default (no database server needed), and also supports MySQL and PostgreSQL as required by the assignment.

---

Features
• Products with arbitrary properties (options)
• Filtering products by property values (multi-select)
• Catalog pagination, 40 products per page
• Clean architecture with API Resource classes
• Full input validation with custom messages
• API response localization (English/Russian, switchable per request)
• Optimized, cached database queries
• Seeders with realistic sample data
• Soft-delete support for products
• Query scopes for filtering (active products, in stock)
• Database indexes for performance
• Factories for testing
• Artisan command to clear the filter cache

---

Requirements
• PHP 8.2+
• Composer
• Node.js + npm
• Any PHP stack you like: the built-in server (`php artisan serve`), Laravel Herd, Valet, Sail, etc. — nothing specific is required
• No database server required: SQLite is used by default. See step 3 for MySQL/PostgreSQL

---

# Setup

1. Clone the repository

```bash
git clone https://github.com/n00rd1/laravel-product-catalog-api.git
cd laravel-product-catalog-api
```

2. Install dependencies

```bash
composer install
npm install
```

3. Configure the environment

```bash
cp .env.example .env
php artisan key:generate
```

`.env.example` is already configured for SQLite — nothing else to install, just create the database file:

```bash
touch database/database.sqlite
```

If you'd rather use MySQL or PostgreSQL (as the assignment requires), set your connection details in `.env`, e.g. for PostgreSQL:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=catalog_api
DB_USERNAME=root
DB_PASSWORD=
```

and create the database:

```bash
psql -U root -h 127.0.0.1 -p 5432 -d postgres -c "CREATE DATABASE catalog_api;"
```

4. Run migrations

```bash
php artisan migrate:fresh
```

5. (Optional) Seed sample data

```bash
php artisan db:seed
```

6. Build frontend assets (only needed for the Breeze pages, not the API itself)

```bash
npm run build
```

7. Start the server

```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000/api/products`.

8. (Optional) Run the tests

```bash
php artisan test
```

---

# Database structure

1. `products` — products
   `(id, name, price, quantity, …)`
2. `properties` — product properties
   `(id, name)`
3. `product_property_values` — property values per product
   `(id, product_id, property_id, value)`

---

# Development overview

1. Migrations created for all tables and run.
2. Models created: Product, Property, ProductPropertyValue.
3. Relationships configured between the models.
4. A seeder implemented for sample data.
5. An API controller created.
6. `GET /api/products` route wired up, with filtering and pagination.
7. Filtering by product options implemented via parameters like: `/api/products?properties[color][]=white&properties[color][]=blue&properties[brand][]=Philips`
8. Pagination implemented at 40 products per page.

---

### Example API request

`GET /api/products?properties[color][]=white&properties[color][]=blue&properties[brand][]=Philips&page=1`

### Example response:

```json
{
    "filters": {
        "Цвет": ["белый", "чёрный", "синий", "красный"],
        "Бренд": ["Philips", "Xiaomi", "Samsung"],
        "Материал": ["металл", "пластик", "стекло"]
    },
    "products": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Настольный светильник",
                "price": "2300.00",
                "quantity": 10,
                "properties": [
                    {
                        "name": "Цвет",
                        "value": "белый"
                    },
                    {
                        "name": "Бренд",
                        "value": "Philips"
                    }
                ]
            }
        ],
        "per_page": 40,
        "total": 124,
        "last_page": 4,
        "from": 1,
        "to": 40
    }
}
```

Property names and values are seed data (Russian, matching the original assignment's example — "Цвет" = Color, "Настольный светильник" = Desk lamp), not translated strings; they come from the database, not from the app's localization.

A ready-to-run curl example (after `php artisan serve` and `php artisan db:seed`):

> Non-ASCII characters in a query string must be URL-encoded in full — both
> the key (property name) and the value. `curl --data-urlencode name=value`
> only encodes the part after `=`, so a non-ASCII key needs encoding up
> front (e.g. `python3 -c "from urllib.parse import quote; print(quote('properties[Цвет][]'))"`).
> Otherwise PHP treats the request as malformed and won't respond.

```bash
curl --location --globoff 'http://127.0.0.1:8000/api/products?properties[%D0%A6%D0%B2%D0%B5%D1%82][]=%D0%B1%D0%B5%D0%BB%D1%8B%D0%B9'
```

(`%D0%A6%D0%B2%D0%B5%D1%82` = "Цвет" (Color), `%D0%B1%D0%B5%D0%BB%D1%8B%D0%B9` = "белый" (white); filters can be combined via `&properties[Бренд][]=...`, but with randomly seeded data, combining several filters at once may legitimately return zero results)

---

# API response language

The API responds in English by default. Switch the language of validation and status messages with either:

- a query parameter: `?lang=ru`
- an `Accept-Language: ru` header

Supported locales are `en` and `ru`; any other value, or no value at all, falls back to English.

```bash
curl -X POST "http://127.0.0.1:8000/api/products?lang=ru" -d '{}' -H "Content-Type: application/json"
# => {"message":"Название товара обязательно. (and 2 more errors)", ...}
```

---

# Additional commands

## Clear the filter cache
```bash
php artisan products:clear-cache
```

## Generate sample data with factories
```bash
# Create 100 products
php artisan tinker
>>> App\Models\Product::factory(100)->create()

# Create products with specific states
>>> App\Models\Product::factory()->active()->inStock()->create()
>>> App\Models\Product::factory()->inactive()->outOfStock()->create()
```

## API Endpoints

| Method | URL | Description |
|--------|-----|--------------|
| GET | `/api/products` | List products with filtering and pagination |
| GET | `/api/products/{id}` | Get a single product |
| POST | `/api/products` | Create a new product |
| PUT/PATCH | `/api/products/{id}` | Update a product |
| DELETE | `/api/products/{id}` | Delete a product (soft delete) |
