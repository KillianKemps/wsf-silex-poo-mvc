<?php

use Blog\Controller;

Class HomeController extends Controller
{

    /**
     * Affiche la home page
     */
    public function getIndex()
    {
        $this->data['user'] = $this->isLogged();

        $articles = $this->app['sql']->query('SELECT * FROM  articles');
        $this->data['articles'] = $articles->fetchAll();

        return $this->app['twig']->render('home.twig', $this->data);
    }

}
