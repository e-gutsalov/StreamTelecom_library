# Тестовое задание PHP Dev

Спроектировать и реализовать БД и сервис для библиотеки.

В БД заложить возможность хранить книги(может быть больше 1 экземпляра), 
возможность поиска по автору(авторов может быть не 1), 
возможность выдать книгу читателю, возможность списать книгу(утрата и тд).

Должны быть следующие методы.

- Добавить книгу с указанием авторов
- Получить все книги
- Выдать книгу читателю
- Списать книгу


# Описание работы с API:

## Запуск докера, создание базы и миграции

В .env раскомментировать строчку DATABASE_URL="mysql://root:rootroot@127.0.0.1:48700/StreamTelecom_library"

composer docker-up 
composer docker-down
composer ddc // создать БД
composer dmm // применить миграции

## Примеры запросов приведены в директории http в корне проекта

### Добавить книгу с указанием авторов

POST http://localhost:8000/api/add/book
Content-Type: application/json

{
"name": "Преступление и наказание",
"yearPublication": "1898",
"ISBN": "1234567890",
"count": 15,
"authors": [
{
"id": 1,                        // Если на момент добавления книги автор известен(при добавлении ещё экземпляров), то спускается id автора, если нет то id === null
"name": "Фёдор",
"surname": "Достоевский",
"patronymic": "Михайлович"
}
]
}

### Получить все книги

POST http://localhost:8000/api/all/books
Content-Type: application/json

### Выдать книгу читателю

POST http://localhost:8000/api/give/book
Content-Type: application/json

{
"ISBN": "123456789",
"reader": {
"id": 1,                        // Если читатель известен то спускается его id, если нет то null, и создается новый читатель 
"name": "Олег",
"surname": "Иванов",
"patronymic": "Михайлович"
}
}

### Списать книгу

POST http://localhost:8000/api/discard/book
Content-Type: application/json

{
"ISBN": "1234567890",
"reason": "Утрата"
}

### Возможность поиска по автору(авторов может быть не 1)

POST http://localhost:8000/api/search/book
Content-Type: application/json

{
"authors": [
{
"name": "Фёдор",
"surname": "Достоевский",
"patronymic": "Михайлович"
},
{
"name": "Петр",
"surname": "Сидоров",
"patronymic": "Андреевич"
}
]
}