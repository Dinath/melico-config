# Melico-config BETA

This french project gives you the advantages of generating your free Android application from this web-service.

## Getting Started

The user justs need to download the zip from [original website](https://meli.co).

Supported engines are currently:

* Wordpress
* ...

Feel free to provide a pull request if you want to add yours.

### Prerequisites

Just clone the project, no need to ```composer install ...```.

### Installing / Deployment

Be sure your server are enabled. 

**Web server**  can be *Apache*, *nginx*, whatever you want...
**Database server** is MySQL / MariaDB.

```
systemctl start httpd
systemctl start mariadb / mysql
```

## Running the tests

If you want to test the application.

```
cd ../melico-config
php -S localhost:8080 -t public -ddisplay_errors=1
```

## Important

The application only supports.


* Looking for articles using pagination 

```/api/get/articles/0```

* Looking for articles using text in title 

```/api/get/articles/find/text```

* Getting the number of articles 

```/api/get/articles/count```

* Send an email with your credential 

```/api/post/email```

## Built With

* [Composer](https://getcomposer.org/) which is provided within the project

## Contributing

You can help me to improve the web-service as well !

But if you just want to implement your CMS, please use ```Example.php``` file in ```engine/``` folder.

## Authors

* **Alexandre Soyer** 

## Acknowledgments

This project is free, check out the french website.

[https://meli.com](https://meli.co)
