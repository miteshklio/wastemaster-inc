# Config
# NOTE: Check 'mysql_secure.sh' as well as pillar for other config stuff.

{% set mysql_root_pass = pillar['mysql']['root_pass'] %}

# Install software-properties-common
software-properties-common:
  pkg:
   - installed
   - name: software-properties-common

# Install mariadb
mariadb-server-debconf:
  debconf.set:
    - name: mariadb-server-10.0
    - data:
        mysql-server/root_password:
          type: string
          value: {{ mysql_root_pass }}
        mysql-server/root_password_again:
          type: string
          value: {{ mysql_root_pass }}
        mysql-server/start_on_boot:
          type: boolean
          value: true
    - require:
      - cmd: mariadb-repo
    - require_in:
      - pkg: mariadb-server

update:
  cmd.run:
    - name: sudo apt-get update
    - require:
      - cmd: mariadb-repo

mariadb-keys:
  cmd.run:
    - name: sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xcbcb082a1bb943db

mariadb-repo:
  cmd.run:
    - name: sudo add-apt-repository 'deb [arch=amd64,i386] http://ftp.osuosl.org/pub/mariadb/repo/10.0/ubuntu trusty main'
    - require:
      - cmd: mariadb-keys

mariadb-server-install:
  pkg.installed:
    - force_yes: True
    - name: mariadb-server
    - require:
      - cmd: update
      - debconf: mariadb-server-debconf

# Make sure mysql gets started at boot time
mysql-rcd:
  cmd:
    - run
    - name: update-rc.d mysql defaults
    - require:
      - pkg: mariadb-server-install

# Restart
mysql-restart:
  cmd:
    - run
    - name: service mysql restart
    - require:
      - pkg: mariadb-server-install

# Check this file for the mysql user and pass for the app
mysql-install-file:
  file:
    - managed
    - name: /etc/mysql_secure.sh
    - source: salt://mariadb/files/mysql_secure.sh
    - template: jinja
    - require:
      - cmd: mysql-restart

mysql-install-file-execute:
  cmd:
    - run
    - name: chmod a+x /etc/mysql_secure.sh
    - require:
      - file: mysql-install-file

mysql-install-run:
  cmd:
    - run
    - name: /etc/mysql_secure.sh
    - require:
      - cmd: mysql-install-file-execute