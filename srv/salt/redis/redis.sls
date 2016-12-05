# Config

python-software-properties:
  pkg.installed:
    - name: python-software-properties

redis-repo:
  cmd.run:
    - name: sudo add-apt-repository -y ppa:rwky/redis
    - require:
      - pkg: python-software-properties

update:
  cmd.run:
    - name: sudo apt-get update
    - require:
      - cmd: redis-repo

redis-server:
  pkg.installed:
    - name: redis-server
    - require:
      - cmd: update
  service:
    - running
    - enable: True
    - name: redis-server
    - require:
      - pkg: redis-server

redis-restart:
  cmd.run:
    - name: sudo service redis-server restart
    - require:
      - pkg:  redis-server