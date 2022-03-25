#!/bin/bash
#docker run --name tomcat -it --rm -p 8888:8080 tomcat:9.0
docker run --name tomcat -d --rm -p 8888:8080 tomcat:9.0-alpine
# сборка alpine содержит в себе все необходимое для запуска
# для остановки контейнера набираем "docker stop tomcat", после остановки он сам удалится (флаг --rm)