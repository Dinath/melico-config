# Melico-config BETA

This french project gives you the advantages of generating your free Android application from this web-service.

## Getting Started

The user justs need to download the zip from [original website](https://melico.fr).

Supported engines are currently:

* [Wordpress](https://wordpress.org)
* [Joomla](https://joomla.org)

Feel free to provide a pull request if you want to add yours.

### Prerequisites

Just clone the project, no need to ```composer install ...```. Modules are already in there.

### Installing / Deployment

Be sure your servers are enabled. 

* **Web server**  can be *Apache*, *nginx*, whatever you want...
* **Database server** is MySQL / MariaDB, maybe I will try to extends the ability of the app with other databases, but it is not planned for now.

```
systemctl start httpd
systemctl start mariadb / mysqld
```

## Production

Upload project or ```git clone``` it to a direcoty in your web-server.


### Security 

```
chown -R httpd:httpd /var/www/melico
chmod -R 755  /var/www/melico
chcon -R -t httpd_sys_rw_content_t /var/www/melico
```

### Apache deployment

Just put the *unzipped file in a directoy* where a website pointed to.


## Running the tests

If you want to test the application, in the project *launch the command*.

```
php -S localhost:8080 -t . -ddisplay_errors=1
```

Go to the **configuration web page**.

```
http://localhost:8080
```

Of course, you need to have a website with supported engines installed.

## Contributing

I will in the future **import in the application new engines** like Drupal, MediaWiki, Ghost... 

But if you want to contribute to the project, **you can fork the project and import your own**.

A class named ```Example.php``` in the ```engine/``` folder would help you. You can also see ```WordPress.php``` and ```Joomla.php``` which are the first engine classes made by the author.

## Important

The application only supports these routes (for now).

### Looking for articles using pagination 

```
/api/get/articles/0
/api/get/articles/10
```

### Looking for articles using text in title 

```
/api/get/articles/find/linux
/api/get/articles/find/alex
```

### Getting the number of articles 

```
/api/get/articles/count
```

### Send an email with your credential 

```
/api/post/email
```

## Built With

The application has been built only with PHP (including [TWIG](http://twig.sensiolabs.org/) for HTML template) for compatibility with CMS. No *JavaScript* needed.

* [PHP 7.1.1](https://secure.php.net/) as programming language.
* [Slim 3.5](https://www.slimframework.com/) for webservice.
* [Composer](https://getcomposer.org/) for dependencies.

## Authors

* **Alexandre Soyer** 

Join me ?

## Acknowledgments

This project is free, check out the french website.

The website is not online for now, I am actually building the new website in [Angular](https://angular.io).
