<?php

use Blog\Controller;

Class HomeController extends Controller
{

    /**
     * Affiche la home page
     */
    public function getIndex($idTag = null)
    {
        $this->data['user'] = $this->isLogged();

        $article = new Article($this->app);
        $this->data['articles'] = $article->getAllArticles($idTag);

        return $this->app['twig']->render('home.twig', $this->data);
    }

    public function getArticle($idArticle)
    {
        $article = new Article($this->app);
        $this->data['article'] = $article->getArticle($idArticle);

        $tag = new Tag($this->app);
        $tags = $tag->getTagsByArticle($idArticle);

        $twitter = new Tweet($this->app);

        foreach ($tags as $tag) {
            $result = $twitter->getTweetByApi($tag['name']);
            $twitter->saveTweet($result);
            $results[] = $result;
        }

        $this->data['tweets'] = $results;

        return $this->app['twig']->render('article.twig', $this->data);
    }

    public function postComment()
    {
        if(!$this->isLogged()) {
            return $this->app->redirect(
                $this->app['url_generator']->generate('home')
            );
        }

        $comment = $this->app['request']->get('comment');
        $idArticle = $this->app['request']->get('idArticle');

        $sql = "INSERT INTO  comments (
                    id,
                    id_articles,
                    comment
                )
                VALUES (
                    NULL,
                    :id_articles, 
                    :comment
                )";

        $arguments = array(
            ':id_articles' => $idArticle,
            ':comment' => $comment
        );

         if (!empty($comment)) {
            $comm = new Comment($this->app);

            $comm->create($comment, $idArticle);
        }

        return $this->getIndex();
    }

}
