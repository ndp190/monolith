## To debug phpunit or any script from command line, follow these steps:

1. Install php and xdebug on host machine
```
sudo apt-get install php7.0-xdebug php7.0
```
2. Configure xdebug on host machine
```
sudo vim /etc/php/7.0/mods-available/xdebug.ini
```
Content:
```
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_host=127.0.0.1
xdebug.remote_cookie_expire_time=86400
xdebug.remote_port=9000
xdebug.remote_autostart=1
xdebug.idekey="PHPSTORM"
```
3. Set break points and listen for PHP debug connections
![Debug](../images/listen.png)
