init: docker-down-clear \
	api-clear \
	docker-pull docker-build docker-up \
	api-init


up: docker-memory docker-up
down: docker-down
clear: docker-down-clear
restart: clear up
check: lint analyze test
lint: api-lint
analyze: api-analyze
test: api-test
test-unit: api-test-unit
test-unit-coverage: api-test-unit-coverage
test-functional: api-test-functional
test-functional-coverage: api-test-functional-coverage

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build

docker-memory:
	sudo sysctl -w vm.max_map_count=262144

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf storage/debugbar/*'
	# docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf storage/app/public/*'
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf public/build'

api-init: api-node-init api-composer-install api-permissions api-generate-app-key api-migrate-database

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod -R 755 .
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod -R 777 storage

api-copy-to-env:
	cat api/.env.example > api/.env

api-generate-app-key:
	docker compose run --rm api-php-cli php artisan key:generate

api-composer-install:
	docker compose run --rm api-php-cli composer install

api-start-queue:
	docker compose run --rm api-php-cli php artisan queue:work

api-lint:
	docker compose run --rm api-php-cli composer lint
	docker compose run --rm api-php-cli composer cs-check

api-test:
	docker compose run --rm api-php-cli composer test

api-migrate-database:
	docker compose run --rm api-php-cli php artisan migrate

api-migrate-database-refresh:
	docker compose run --rm api-php-cli php artisan migrate:refresh

api-test-unit:
	docker compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-unit-coverage:
	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=unit

api-test-functional:
	docker compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-functional-coverage:
	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=functional

api-analyze:
	docker compose run --rm api-php-cli composer psalm

api-cs-fix:
	docker compose run --rm api-php-cli composer php-cs-fixer fix

api-clear-cache-laravel:
	docker compose run --rm api-php-cli php artisan cache:clear
	docker compose run --rm api-php-cli php artisan config:clear

api-node-init: api-yarn-install api-ready api-vite-build

api-yarn-install:
	docker compose run --rm api-node-cli yarn install

api-ready:
	docker run --rm -v ${PWD}/frontend:/app -w /app alpine touch .ready

api-vite-build:
	docker compose run --rm api-node-cli yarn run build

api-vite-remove:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf public/build'
