FROM hub.c.163.com/library/php:latest

RUN apt-get update -y
RUN apt-get install cron -y
RUN apt-get install git -y
RUN apt-get install vim -y

# 复制本地目录到docker容器
ADD . /usr/src/cron

# 进入docker容器目录
WORKDIR /usr/src/cron

RUN chmod a+x ./init-first.php
RUN chmod a+x ./init-first.sh

## CMD "./init-first.php"
