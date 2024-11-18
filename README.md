## Описание проекта

Небольшой сайт-статейник, реализованный на Laravel для бэкенда и Vue.js для фронтенда. Для удобства развёртывания использован Docker, и файлы `.env` оставлены как на фронте, так и на бэке.

## Развертывание проекта

1. **Клонируйте репозиторий:**


```bash
	git clone https://github.com/Zasetsky/blog.git
	cd blog
```

2. **Запустите Docker Compose:**

```bash
	docker-compose up --build -d
```
	
3. **После создания контейнеров вы можете проследить за установкой зависимостей:**

```bash
	docker-compose logs -f
```

Приложение будет доступно по адресу: `http://localhost:8000`

## Режим разработки

### Бэкенд:

```bash
	cd blog-api
	docker-compose up --build
```
	
### Фронтенд:

```bash
	cd blog-front
	npm install
	npm run serve
```

Фронтенд будет доступен по адресу: `http://localhost:8080`

Бэкенд будет доступен по адресу `http://localhost:8000`