init: down up backend-install frontend-install frontend-build

up:
	docker-compose up -d --build

down:
	docker-compose down

start:
	docker-compose start

stop:
	docker-compose stop

backend-install:
	docker-compose exec backend-php-cli composer install

frontend-install:
	docker-compose exec frontend-nodejs npm install

frontend-build:
	docker-compose exec frontend-nodejs npm run build