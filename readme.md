# Vault Innovation Backend Starter Kit
![Vault Innovation](https://avatars1.githubusercontent.com/u/4762199)

Hi! Looks like you're starting a new project! How exciting!

We've taken the liberty of helping you get started with your project and including things like: 

- A Standard Vagrant Box and Server Build Scripts
- A Simple PHPunit Testing Framework
- Crazy Easy Project Installation
- Starter Admin and Authentication

### Installation

#### Get your VM running

Getting your VM running (should you need one), is pretty simple. 

Our VM is created using [Vagrant](https://www.vagrantup.com), so make sure you have it installed. You can download vagrant [here](https://www.vagrantup.com/downloads.html).

After you have Vagrant installed, getting the VM up and running is pretty easy. 

```
vagrant up
echo "192.168.56.110 <name_of_new_app>.dev" > /etc/hosts
```
Everything is installed via [Salt](http://saltstack.com) during your first time bringing your vagrant box up.

### Coding Standards