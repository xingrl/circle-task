安装
--$ docker build -t cuix-cron .
$ docker run -it cuix-cron /bin/bash

初始化
--# ./init-first.php

scp -r /Users/xingwenge/workspace/localhost/circle-task/ root@120.77.177.189:/root/test

docker cp {container-id}:{file} {file}
docker cp /root/test/circle-task 1160962da99a:/www

docker run -v /root/test:/mnt -i -t hub.c.163.com/library/php bash