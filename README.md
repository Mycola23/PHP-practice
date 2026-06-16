# ✈️ Airport Planes API

Навчальний проєкт — реалізація однакового REST API для управління реєстром літаків аеропорту на двох PHP-фреймворках: **Symfony** та **Laravel**.

---

## 📋 Зміст

- [Про проєкт](#про-проєкт)
- [Структура проєкту](#структура-проєкту)
- [API Ендпоінти](#api-ендпоінти)
- [Запуск — Symfony](#запуск--symfony)
- [Запуск — Laravel](#запуск--laravel)
- [Документація Postman](#документація-postman)
- [Автор](#автор)

---

## Про проєкт

Реалізовано два ідентичні за функціональністю REST API:

|                  | Symfony                               | Laravel                                   |
| ---------------- | ------------------------------------- | ----------------------------------------- |
| **Фреймворк**    | Symfony 7+                            | Laravel 11+                               |
| **Контролер**    | `TestController`                      | `PlaneController`                         |
| **Маршрути**     | PHP-атрибути `#[Route]`               | `routes/web.php` з `Route::prefix('api')` |
| **JSON-парсинг** | `json_decode($request->getContent())` | `$request->json()->all()`                 |
| **База даних**   | In-memory масив                       | In-memory масив                           |

Обидва API підтримують повний **CRUD** для сутності `Plane`:

| Поле      | Тип     | Обов'язкове | Опис                     |
| --------- | ------- | ----------- | ------------------------ |
| `id`      | integer | авто        | Унікальний ідентифікатор |
| `model`   | string  | ✅          | Модель літака            |
| `airline` | string  | ✅          | Авіакомпанія             |

---

## Структура проєкту

```
airport-api/
├── symfony/
│   └── src/Controller/TestController.php
├── laravel/
│   ├── app/Http/Controllers/PlaneController.php
│   └── routes/web.php
├─collection for symfony.json
└─collection for laravel.json
```

---

## API Ендпоінти

Обидва API реалізують однакові маршрути під префіксом `/api`:

| Метод         | Маршрут            | Дія                  | Статуси       |
| ------------- | ------------------ | -------------------- | ------------- |
| `GET`         | `/api/planes`      | Список усіх літаків  | 200           |
| `POST`        | `/api/planes`      | Створити літак       | 201, 400, 500 |
| `GET`         | `/api/planes/{id}` | Отримати літак за ID | 200, 404      |
| `PUT / PATCH` | `/api/planes/{id}` | Оновити літак        | 200, 400, 404 |
| `DELETE`      | `/api/planes/{id}` | Видалити літак       | 200, 404      |

### Приклад відповіді `GET /api/planes`

```json
{
    "status": "success",
    "data": [
        { "id": 1, "model": "Boeing 747", "airline": "Ukraine International" },
        { "id": 2, "model": "Airbus A320", "airline": "Wizz Air" }
    ]
}
```

---

## Запуск — Symfony

### Вимоги

- PHP 8.2+
- Composer
- Symfony CLI _(рекомендовано)_

### Кроки

```bash
# 1. Перейти до папки проєкту
cd symfony/

# 2. Встановити залежності
composer install

# 3. Запустити сервер
symfony serve
# або без Symfony CLI:
php -S localhost:8000 -t public/
```

### Перевірка

```bash
curl http://localhost:8000/api/planes
```

> ⚠️ Symfony за замовчуванням запускається на `https://127.0.0.1:8000`. При використанні `symfony serve`

---

## Запуск — Laravel

### Вимоги

- PHP 8.2+
- Composer

### Кроки

```bash
# 1. Перейти до папки проєкту
cd laravel/

# 2. Встановити залежності
composer install

# 3. Скопіювати конфіг середовища
cp .env.example .env

# 4. Згенерувати ключ додатку
php artisan key:generate

# 5. Запустити сервер
php artisan serve
```

### Перевірка

```bash
curl http://localhost:8000/api/planes
```

> ⚠️ У запитах до Laravel рекомендую передавати заголовок `Accept: application/json`, щоб у разі помилки отримати JSON, а не HTML-сторінку.

---

## Документація Postman

До обох API додано повні колекції Postman у форматі **Collection v2.1.0**.

### Імпорт

1. Відкрити **Postman**
2. `File → Import`
3. Вибрати потрібний файл колекції

### Файли колекцій

| Фреймворк | Файл                                               |
| --------- | -------------------------------------------------- |
| Symfony   | `collection for symfony.json  ` |
| Laravel   | `collection for laravel.json ` |

### Змінні середовища

Обидві колекції використовують змінну `{{baseUrl}}`.

| Змінна    | Значення за замовчуванням |
| --------- | ------------------------- |
| `baseUrl` | `http://localhost:8000`   |

Щоб змінити — відкрити колекцію → `Variables` → відредагувати `baseUrl`.

### Що включено в документацію

Кожен ендпоінт містить:

- Опис маршруту та дії
- Таблицю параметрів (path / body)
- Приклади **всіх можливих відповідей** (успішні та помилкові)
- Реальні JSON-тіла у вкладці **Examples**

---
## Prompt for Claude Code


`На основі наданих файлів коду, тобі треба зробити повну документацію для RESTFull API у форматі json, для postman. Мають бути прописані всі endpoints, запити до них і відповіді, статус-кодів... `


---
## Автор

|             |                              |
| ----------- | ---------------------------- |
| **Виконав** | Литвиненко Микола            |
| **Група**   | ВТ-24-1                      |
| **Email**   | vt241_lmv@student.ztu.edu.ua |
