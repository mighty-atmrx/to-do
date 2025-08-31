# ToDo API

Простой **CRUD API для ToDo-листа** с поддержкой авторизации пользователей.
Проект использует **Laravel 12**, упакован в Docker-контейнеры с **Nginx, backend(PHP) и MySQL**.

---

## 📌 Основные возможности

* Регистрация и аутентификация пользователей через API (**Sanctum**).
* CRUD операции для задач:

    * Получение всех задач
    * Получение только своих задач
    * Создание задачи
    * Просмотр конкретной задачи
    * Обновление
    * Удаление
* Защищённые маршруты для авторизованных пользователей.
* Валидация данных через **Form Requests**.
* Полностью готов к использованию в SPA или мобильных приложениях.

---

## 🛠 Технологии

* **Laravel 12**
* **PHP 8+**
* **MySQL 8**
* **Nginx**
* **Docker & Docker Compose**
* **Sanctum** для API токенов
* **REST API** с JSON

---

## 🐳 Docker-контейнеры

| Контейнер   | Назначение        | Порт                 |
| ----------- | ----------------- | -------------------- |
| `backend`   | PHP-FPM с Laravel | 9000 (внутри Docker) |
| `webserver` | Nginx             | 8000                 |
| `db`        | MySQL 8           | 3306                 |

### Команды Docker

* Запуск контейнеров:

```bash
docker-compose up -d --build
```

* Остановка:

```bash
docker-compose down
```

* Логи контейнера:

```bash
docker-compose logs -f backend
docker-compose logs -f webserver
docker-compose logs -f db
```

* Подключение к контейнеру:

```bash
docker-compose exec backend bash
docker-compose exec db mysql -u root -p
```

---

## ⚙ Настройка

1. Склонировать репозиторий:

```bash
git clone <repo_url>
cd <repo_folder>
```

2. Создать `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=todo
DB_USERNAME=todo
DB_PASSWORD=12345678
DB_ROOT_PASSWORD=root
```

3. Собрать и запустить Docker:

```bash
docker-compose up --build -d
```

4. Применить миграции:

```bash
docker-compose exec backend php artisan migrate
```

---

## 🔗 API маршруты

### Аутентификация

| Метод | URL             | Описание                               |
| ----- | --------------- | -------------------------------------- |
| POST  | `/api/register` | Регистрация пользователя               |
| POST  | `/api/login`    | Вход пользователя                      |
| POST  | `/api/logout`   | Выход (Bearer токен)                   |
| GET   | `/api/me`       | Получение данных текущего пользователя |

### Задачи (Tasks)

Все маршруты требуют токен **Bearer**:

| Метод  | URL               | Описание                  |
| ------ |-------------------|---------------------------|
| GET    | `/api/tasks`      | Список всех задач         |
| GET    | `/api/my-tasks`   | Список задач пользователя |
| POST   | `/api/tasks`      | Создать новую задачу      |
| GET    | `/api/tasks/{id}` | Получить одну задачу      |
| PUT    | `/api/tasks/{id}` | Обновить задачу           |
| DELETE | `/api/tasks/{id}` | Удалить задачу            |


## ⚡ Проверка API

* Через **Postman** или **curl**:

```bash
# Регистрация
curl -X POST http://localhost:8000/api/register \
-H "Content-Type: application/json" \
-d '{"name":"John","email":"john@test.com","password":"password123","password_confirmation":"password123"}'

# Логин
curl -X POST http://localhost:8000/api/login \
-H "Content-Type: application/json" \
-d '{"email":"john@test.com","password":"password123"}'

# Получение текущего пользователя (Bearer token)
curl -X GET http://localhost:8000/api/me \
-H "Authorization: Bearer <token>"

# Получение всех задач (Bearer token)
curl -X GET http://localhost:8000/api/tasks \
-H "Authorization: Bearer <token>"

# Получение своих задач (Bearer token)
curl -X GET http://localhost:8000/api/my-tasks \
-H "Authorization: Bearer <token>"

# Создание задачи (Bearer token)
curl -X POST http://localhost:8000/api/tasks \
-H "Authorization: Bearer <token>" \
-H "Content-Type: application/json" \
-d '{"title":"test","description":"description", "status":"in_progress"}'

# Просмотр задачи (Bearer token)
curl -X GET http://localhost:8000/api/tasks/1 \
-H "Authorization: Bearer <token>"

# Обновление задачи (Bearer token)
curl -X PATCH http://localhost:8000/api/tasks/1 \
-H "Authorization: Bearer <token>" \
-H "Content-Type: application/json" \
-d '{"title":"updated test","description":"updated description","status":"completed"}'

# Удаление задачи (Bearer token)
curl -X DELETE http://localhost:8000/api/tasks/1 \
-H "Authorization: Bearer <token>"
```

---

## 💡 Примечания

* Все ответы API в формате **JSON**.
* Для всех защищённых маршрутов используйте **Bearer токен**.
* Проект готов к расширению для SPA, мобильных приложений или фронтенд-фреймворков.
