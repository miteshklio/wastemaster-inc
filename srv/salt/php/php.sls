# php install
php7:
  cmd.run:
    - name: apt-get install -y language-pack-en-base && LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php && apt-key update -y &&apt-get update -y

php:
  pkg:
    - installed
    - names:
      - php7.0-fpm
      - php7.0-mysql
      - php7.0-mcrypt
      - php7.0-cli
      - php7.0-json
    - require:
      - cmd: php7
  service:
    - running
    - enable: True
    - name: php7.0-fpm
    - require:
      - pkg: php
  cmd:
    - run
    - name: service php7.0-fpm restart
    - require:
      - pkg: php

# Setup public_html dir
html-dir:
  cmd.run:
    - name: mkdir -p /home/public_html
    - require:
      - pkg: php7.0-fpm

# Setup app dir
app-dir:
  cmd.run:
    - name: mkdir -p /home/public_html/app
    - require:
      - cmd: html-dir

# Nginx install
nginx:
  pkg:
    - installed
    - name: nginx
  service:
    - running
    - enable: True
    - name: nginx
    - require:
      - pkg: nginx
      - file: /etc/nginx/sites-available/default
      - cmd: app-dir

/etc/nginx/sites-available/default:
  file.managed:
    - source: salt://php/files/app.conf.jin
    - template: jinja
    - require:
      - pkg: nginx

/etc/nginx/sites-enabled/default:
  file.symlink:
    - target: /etc/nginx/sites-available/default
    - require:
      - file: /etc/nginx/sites-available/default

/etc/nginx/nginx.conf:
  file.managed:
    - source: salt://php/files/nginx.conf.jin
    - template: jinja
    - require:
      - file: /etc/nginx/sites-enabled/default

restart:
  cmd.run:
    - name: sudo service nginx restart
    - require:
      - file: /etc/nginx/sites-enabled/default
      - cmd: app-dir
    - watch:
      - file: /etc/nginx/nginx.conf

