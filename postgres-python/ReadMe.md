Host name / server: pgsql-server

соберем образ: docker-compose run django django-admin startproject itproger .
поднимем контейнеры: docker-compose up

поднимается сервис на: http://localhost:8000

сделаем миграцию: docker-compose run django python manage.py migrate
создадим пользователя админки: docker-compose run django python manage.py createsuperuser
после можно смотреть админку: http://localhost:8000/admin/

остановим контейнеры: docker-compose down