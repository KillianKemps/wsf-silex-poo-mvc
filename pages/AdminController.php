<?php

use Blog\Controller;

Class AdminController extends Controller
{

    /**
     * get Article action :
     * Affiche la page /admin
     *
     * @return string  html rendu par twig
     */
    public function getArticle()
    {
        //Si user n'est pas admin redirection
        if(!$this->isAdmin()) {
            return $this->app->redirect(
                $this->app['url_generator']->generate('home')
            );
        }

        $tag = new Tag($this->app);
        $this->data['tags'] = $tag->getAll();

        return $this->app['twig']->render('admin/article.twig', $this->data);
    }


    /**
     * [postArticle description]
     * @return [type] [description]
     */
    public function postArticle()
    {
        if(!$this->isAdmin()) {
            return $this->app->redirect(
                $this->app['url_generator']->generate('home')
            );
        }

        $title = $this->app['request']->get('title');
        $article = $this->app['request']->get('article');
        $tags = $this->app['request']->get('tag');

        if (!empty($title) && !empty($article)) {
            $art = new Article($this->app);

            $idArticle = $art->create($title, $article);

            foreach ($tags as $tag) {
                $art->addTag($idArticle, $tag);
            }
        }

        return $this->getArticle();
    }

    /**
     * Get page create tag
     * @return [type] [description]
     */
    public function getTag()
    {
        //Si user n'est pas admin redirection
        if(!$this->isAdmin()) {
            return $this->app->redirect(
                $this->app['url_generator']->generate('home')
            );
        }

        return $this->app['twig']->render('admin/tag.twig', $this->data);

    }

    public function postTag()
    {
        if(!$this->isAdmin()) {
            return $this->app->redirect(
                $this->app['url_generator']->generate('home')
            );
        }

        $tag = $this->app['request']->get('tag');

        if (!empty($tag)) {
            $t = new Tag($this->app);
            $t->create($tag);
        }

        return $this->getTag();
    }

}
