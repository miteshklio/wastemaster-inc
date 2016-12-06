# Setup public_html dir
html-dir:
  cmd.run:
    - name: mkdir -p /home/public_html

# Setup app dir
app-dir:
  cmd.run:
    - name: mkdir -p /home/public_html/app
    - require:
      - cmd: html-dir

ssl-dir: 
  cmd.run:
    - name: mkdir -p /etc/nginx/ssl
    - require: 
      - cmd: app-dir

# Create self-signed ssl cert
cert: 
  cmd.run:
    - name: openssl req -newkey rsa:4096 -x509 -days 3650 -nodes -out /etc/nginx/ssl/wastemaster.crt -keyout /etc/nginx/ssl/wastemaster.key -subj "/C=US/ST=Illinois/L=Chicago/O=IT/CN=localhost"
    - require: 
      - cmd: ssl-dir

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
    - source: salt://webserver/files/app.production.conf.jin
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
    - source: salt://webserver/files/nginx.production.conf.jin
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

