# MVC Boiler
MVC boilerplate for starting a simple web project in PHP.

Includes the following components:
- Front Controller
- HTTP Router
- Controllers
- HTTP Request and Response objects
- Database Handler factory
- MySQL PDO abstraction
- Database to Entity mapping
- Input Filters
- HTML View construction with partials
- Application Container
- Example Comments System application

###Table of Contents###

1. [Requirements](#requirments)
2. [Installation](#installation)
3. [Running in Development environment](#runningindevelopmentenvironment)
4. [Running on another Server environment](#runninginanotherenvironment)
4. [Todo](#todo)

<a name="requirements"></a>
### Requirements

- PHP 5.6.0 or Higher
- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/)
- [Vagrant](https://www.vagrantup.com/) OR another Server environment

<a name="installation"></a>
### Installation
- Navigate to the project folder

        cd mvc-boiler
- Run composer install to import dev dependencies and enable auto-loading

        composer install

<a name="runningindevelopmentenvironment"></a>
### Running in Development environment (with Vagrant)

- Generate local Homestead files

        php vendor/bin/homestead make
    on Windows machine perform this instead:

        vendor\bin\homestead make
- Boot the virtual machine (and wait for the process to finish)

        vagrant up
- SSH into the virtual box
( If you are on a Windows machine, you need  [Git Bash CLI](https://git-for-windows.github.io/) since Windows CLI does not support SSH )

        vagrant ssh
- Navigate to the project folder (__! inside the virtual box__)

        cd mvc-boiler
- Perform database migration and seeding (__! inside the virtual box__)

        php bootstrap/init.php

- Back on your host machine, open the [**Homestead.yaml**](Homestead.yaml) file and copy the IP the address. For example:

        ip: "192.168.10.33"
- Open your browser and navigate to this IP address ( by default [http://192.168.10.33/](http://192.168.10.33/) )

- Enjoy!

- In case you don't see the project's website ( for example this particular IP address has already been taken by another application on this machine ), change the IP address's last digit to something else, 80 for example.
- Reload the vagrant box

        vagrant reload
- Try the new IP in your browser [http://192.168.10.80/](http://192.168.10.80/) and Enjoy!

<a name="runninginanotherenvironment"></a>
### Running on another Server environment

- Place in the project's directory and make other server specific configurations.

- Run composer install to enable auto-loading

        composer install

- Update database MySQL connection configurations located in [**config/database.json**](config/database.json) file, so they fit with your Server's MySQL configurations.
- Enjoy!
