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
        $sql = 'SELECT *
                FROM tags';

        $tags = $this->app['sql']->query($sql);
        $this->data['tags'] = $tags->fetchAll(PDO::FETCH_ASSOC);

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

        if (!empty($title) && !empty($article)) {
            $sql = "INSERT INTO articles (
                id ,
                title ,
                body
            )
            VALUES (
                NULL ,
                :title,
                :body
            )";

            $arguments = array(
                ':title' => $title,
                ':body' => $article,
            );

            $this->app['sql']->prepareExec($sql, $arguments);
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
            $sql = "INSERT INTO tags (
                id ,
                name
            )
            VALUES (
                NULL ,
                :name
            )";

            $arguments = array(
                ':name' => $tag,
            );

            $this->app['sql']->prepareExec($sql, $arguments);
        }

        return $this->getTag();
    }

}
