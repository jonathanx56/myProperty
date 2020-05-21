echo Uploading Application container
docker-compose up -d

echo Copying the configuration example file
docker exec -it myproperty-app cp .env.example .env

echo Install dependencies
docker exec -it myproperty-app composer install

echo Generate key
docker exec -it myproperty-app php artisan key:generate

echo Make migrations
docker exec -it myproperty-app php artisan migrate

echo Make seeds
docker exec -it myproperty-app php artisan db:seed

echo Generate jwt:secret
docker exec -it myproperty-app php artisan jwt:secret

echo config cache
docker exec -it myproperty-app php artisan config:cache

echo Information of new containers
docker ps -a

echo compose php install
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer install
