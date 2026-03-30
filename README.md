<img src="https://raw.githubusercontent.com/bbalet/jorani/master/assets/images/logo_simple.png" width="80" align="left" hspace="10">

Jorani is a Leave Management System developed in PHP8.1+/MySQL8+ with an MIT licence.
Jorani is designed to provide simple leave and overtime request workflows for small organizations.

## Features

* Leave request approval workflow (1 validator).
* Overtime request approval workflow (1 validator).
* Leave balance report (filtered by department).
* Monthly presence report.
* Export to spreadsheet and reporting.
* Calendars of leaves (individual, team, collaborators, etc.).
* Describe your organization in a tree structure and attach employees to entities, define a supervisor per entity.
* Non working days (weekends and day offs) can be imported or defined on a contract.
* REST API (OAuth2) fully documented and examples with PHP clients.
* SSO Authentication (SAML, OpenLDAP, AD, etc.).
* Available in English, French, Spanish, Italian, Polish, Portuguese, German, Dutch, Russian, Ukrainian, Persian, Khmer, Vietnamese, Czech, Arabic and Turkish.

## Installation

### Docker

Edit the .env file to set your environment variables.

```bash
docker compose up
```

Or if you want a development environment, use the override file:

```bash
docker compose up -d
```

### Manual

**IMPORTANT:** If you want to install Jorani in production, please download it from the Release tab.

* If you use Apache, **mod_rewrite must be activated and the config must allow overwriting settings with .htaccess file**.
* Download or clone Jorani. If you clone, please update the vendor folder with `composer`.
* Upload the content of this folder on your server (in <code>/var/www/...</code>).
* Create a database with <code>/legacy/sql/initi/jorani.sql</code> script.
* Create a user with SELECT, INSERT, UPDATE, DELETE, EXECUTE permissions on the database (**Jorani uses MySQL functions**).
* Update <code>/legacy/application/config/database.php</code> according to your database settings.
* Update the end of <code>/legacy/application/config/email.php</code> with your e-mails settings.
* Update the end of <code>/legacy/application/config/config.php</code> if you want to change the default behaviour.
* Check your installation with the <code>requirements.php</code> page at the root of your installation (e.g. http://localhost/jorani/requirements.php).
* The default administrator is *jorani* and password is *jorani* (or *jdoe* and *jdoe* for a user).

## Contribute

* Help us to translate the software in your language https://www.transifex.com/projects/p/jorani
* Suggest ideas, declare bugs with Github's issue tracking system.
* Join the developers chat on gitter [![ https://gitter.im/bbalet/jorani](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/bbalet/jorani?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
* User group : https://github.com/jorani/jorani/discussions (legacy forum : https://groups.google.com/forum/?hl=en#!forum/jorani)

## Credits

### Contributors

* Github and Google group users for their ideas and tests.
* All participants of the Transifex project.
