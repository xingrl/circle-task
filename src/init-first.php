#!/usr/local/bin/php
<?php
# 启动crontab服务
exec( '/etc/init.d/cron start' );

$home = '/usr/src/cron';

# 安装cron脚本
$cronJobs = [
    'gd/egg.php',
];

foreach( $cronJobs as $k => $job ){
    exec("(crontab -l; php {$home}/{$job} >> /tmp/cron-{$k}.log ) | crontab");
}

# exec("{$home}/init-first.sh");
