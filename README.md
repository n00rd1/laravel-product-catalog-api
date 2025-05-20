# Laravel Product Catalog API

## Тестовое задание: REST API на Laravel 12 для каталога товаров с произвольными свойствами (опциями) и фильтрацией по ним.

Разработать API backend на фреймворке Laravel. В качестве БД использовать MySQL, Postgresql. Ожидаемое время выполнения 4 часа. Результат должен быть выложен на github

### Требуемый функционал:
- Необходимо реализовать “каталог товаров”. Товар: название, цена, количество. Свойства (опции) товара: название
- Свойства товара должны быть произвольными т е заполняться в БД
- Реализовать фильтрацию списка товаров с множественным выбором, например GET /products?properties[свойство1][]=значение1_своства1&properties[свойство1][]=значение2_своства1&properties[свойство2][]=значение1_свойства2.
- Нужен api GET метод получения списка товаров (“каталог товаров”) пагинированных по 40
- Необходимо  сделать фильтр товаров по опциям товаров, например, есть товары "настольный светильник", с опциями цвет плафона, цвет арматруы, бренд. Нужно по опциям отфильтровать товары.

Используется PostgreSQL (Laravel Herd).

---

Возможности
• Хранение товаров с произвольными свойствами (опциями)
• Фильтрация товаров по значениям свойств (множественный выбор)
• Пагинация каталога по 40 товаров на страницу
• Чистая архитектура, тестовые сидеры

---

Требования
• PHP 8.2+
• Laravel Herd (или любой другой PHP+PostgreSQL стек)
• PostgreSQL (по умолчанию порт 5432)
• Composer

---

# Установка и запуск

1. Клонируй репозиторий

git clone https://github.com/n00rd1/laravel-product-catalog-api.git
cd laravel-product-catalog-api

2. Установи зависимости

```bash
composer install
```

```bash
npm install
```

3. Настрой файл .env

Используй стандартные параметры подключения к PostgreSQL Herd:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=catalog_api
DB_USERNAME=root
DB_PASSWORD=
```

Создай базу данных, если не создана:

```bash
psql -U root -h 127.0.0.1 -p 5432 -d postgres
CREATE DATABASE catalog_api;
```

4. Сгенерируй ключ приложения

```bash
php artisan key:generate
```

5. Выполни миграции

```bash
php artisan migrate:fresh
```

6. (Опционально) Заполни тестовые данные

```bash
php artisan db:seed
```

7. Запусти сервер

```bash
herd start
```

---

# Структура базы данных

1. products — товары
   `(id, name, price, quantity, …)`
2. properties — свойства товаров
   `(id, name)`
3. product_property_values — значения свойств для товаров
   `(id, product_id, property_id, value)`

---

# Основные шаги разработки

1. Созданы миграции для всех таблиц и выполнены миграции.
2. Созданы модели: Product, Property, ProductPropertyValue.
3. Настроены связи между моделями.
4. Реализован сидер для тестовых данных.
5. Создан контроллер для API.
6. Прописан маршрут GET /api/products с поддержкой фильтрации и пагинации.
7. Реализована логика фильтрации по опциям товаров через параметры вида: `/api/products?properties[цвет][]=белый&properties[цвет][]=синий&properties[бренд][]=Philips`
8.	Реализована пагинация по 40 товаров на страницу.

---

### Пример API-запроса

`GET /api/products?properties[цвет][]=белый&properties[цвет][]=синий&properties[бренд][]=Philips&page=1`

### Ответ (пример):

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "name": "Настольный светильник",
            "price": "2300.00",
            "quantity": 10,
            "properties": [
                {
                    "name": "цвет",
                    "value": "белый"
                },
                {
                    "name": "бренд",
                    "value": "Philips"
                }
            ]
        },
        ...
    ],
    "per_page": 40,
    "total": 124,
    "last_page": 4
}
```

Курл с предзаполненными данными для теста
```curl
curl --location --globoff 'https://catalog-api.test/api/products?properties[%D0%A6%D0%B2%D0%B5%D1%82][]=%D0%B1%D0%B5%D0%BB%D1%8B%D0%B9&properties[%D0%91%D1%80%D0%B5%D0%BD%D0%B4][]=Philips&properties[%D0%9C%D0%B0%D1%82%D0%B5%D1%80%D0%B8%D0%B0%D0%BB][]=%D0%BC%D0%B5%D1%82%D0%B0%D0%BB%D0%BB'
```
