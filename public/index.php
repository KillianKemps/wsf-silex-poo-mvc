<?php

// Autoload Composer
require_once __DIR__.'/../vendor/autoload.php';

//Init silex application
$app = new Silex\Application();


//Debug
$app['debug'] = true;

//load config
require_once __DIR__.'/../config/database.php';

//init Database
$app['sql'] = new Blog\Sql(
    $config['server'],
    $config['database'],
    $config['id'],
    $config['password']
);

//Register twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
//register service url generator
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
//register service session
$app->register(new Silex\Provider\SessionServiceProvider());

//Création route home
$app->get('/', function () use ($app) {
    $c = new HomeController($app);
    return $c->getIndex();
})
->bind('home');

//Création route home
$app->get('/article/{idArticle}', function ($idArticle) use ($app) {
    $c = new HomeController($app);
    return $c->getArticle($idArticle);
})
->bind('article');

$app->get('/filter/{idTag}', function ($idTag) use ($app) {
    $c = new HomeController($app);
    return $c->getIndex($idTag);
})
->bind('filterArticle');

//route user login
$app->get('/login', function () use ($app) {
    $c = new UserController($app);
    return $c->getLogin();
})
->bind('login');

//route user login
$app->post('/login', function () use ($app) {
    $c = new UserController($app);
    return $c->postLogin();
})
->bind('postLogin');

//logout
$app->get('/logout', function () use ($app) {
    $c = new UserController($app);
    return $c->getLogout();
})
->bind('logout');

//route user login
$app->get('/register', function () use ($app) {
    $c = new UserController($app);
    return $c->getRegister();
})
->bind('register');

//route user login
$app->post('/register', function () use ($app) {
    $c = new UserController($app);
    return $c->postRegister();
})
->bind('postRegister');

/*********
*
* ADMIN
*
* ********/

//Création route /admin
$app->get('/admin/articles', function () use ($app) {
    $c = new AdminController($app);
    return $c->getArticle();
})
->bind('getAdminArticle');

//route post /admin
$app->post('/admin/articles', function () use ($app) {
    $c = new AdminController($app);
    return $c->postArticle();
})
->bind('postAdminArticle');

//route get /admin
$app->get('/admin/tags', function () use ($app) {
    $c = new AdminController($app);
    return $c->getTag();
})
->bind('getAdminTags');

//route post /admin
$app->post('/admin/tags', function () use ($app) {
    $c = new AdminController($app);
    return $c->postTag();
})
->bind('postAdminTags');

//run app
$app->run();
