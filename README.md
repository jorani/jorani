<img src="https://raw.githubusercontent.com/bbalet/jorani/master/assets/images/logo_simple.png" width="80" align="left" hspace="10">

Jorani is a Leave Management System developed in PHP/MySQL under an AGPL v3 licence.
Jorani is designed to provide simple leave and overtime request workflows for small organizations.

## Informations / getting help

* Official website : https://jorani.org/
* User group : https://groups.google.com/forum/?hl=en#!forum/jorani

## Status

This project is stable and ready for production. An online demo is available here : https://demo.jorani.org/
Use bbalet / bbalet for login / password.

## Features

* Comprehensive online documentation (French and English).
* Notifications by e-mail (requested, accepted, rejected and new user).
* Leave request approval workflow (1 validator).
* Overtime request approval workflow (1 validator).
* Leave balance report (filtered by department).
* Monthly presence report.
* Export to XLSX (Excel, LibreOffice) in a click (almost all pages of the application).
* HR users can edit any leave or overtime request.
* Set your own contracts and leave types.
* Calendars of leaves (individual, team, collaborators, etc.).
* Describe your organization in a tree structure and attach employees to entities, define a supervisor per entity.
* Non working days (weekends and day offs) can be defined on a contract so as to automatically calculate the duration of a leave and to display them in the calendar.
* REST API (OAuth2) fully documented and examples with PHP clients.
* LDAP and SAML Authentication (OpenLDAP, AD, etc.).
* OAuth2 Authentication (only Google+ at the moment).
* Available in English, French, Spanish, Italian, Portuguese, German, Dutch, Russian, Ukrainian, Persian, Khmer, Vietnamese, Czech, Arabic and Turkish.

## Installation

**IMPORTANT:** If you want to install Jorani in production, please download it from the Release tab.

[See the installation instructions](docs/install/README.md) for advanced configuration. In a nutshell :
* If you use Apache, **mod_rewrite must be activated and the config must allow overwriting settings with .htaccess file**.
* Download or clone Jorani. If you clone, please update the vendor folder with `composer`.
* Upload the content of this folder on your server (in <code>/var/www/...</code>).
* Create a database with <code>/sql/lms.sql</code> script.
* Create a user with SELECT, INSERT, UPDATE, DELETE, EXECUTE permissions on the database (**Jorani uses MySQL functions**).
* Update <code>/application/config/database.php</code> according to your database settings.
* Update the end of <code>/application/config/email.php</code> with your e-mails settings.
* Update the end of <code>/application/config/config.php</code> if you want to change the default behaviour.
* It is recommended to change the private and public RSA keys (in <code>assets/keys</code>).
* Check your installation with the <code>requirements.php</code> page at the root of your installation (e.g. http://localhost/lms/requirements.php).
* The default user is *bbalet* and password is *bbalet*.

## Contribute

* Help us to translate the software in your language https://www.transifex.com/projects/p/jorani
* Suggest ideas, declare bugs with Github's issue tracking system or Google group.
* [Read the TODO list](TODO.md) if you want to know what are the priorities.
* Join the developers chat on gitter [![ https://gitter.im/bbalet/jorani](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/bbalet/jorani?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Credits

### Contributors

* Github and Google group users for their ideas and tests.
* All participants of the Transifex project.
