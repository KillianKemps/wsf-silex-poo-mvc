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

        if ($idTag) {
            $sql = 'SELECT
                    a.id as idArticle,
                    a.title,
                    a.body,
                    t.id as idTags,
                    t.name
                FROM  articles a
                LEFT JOIN articles_tags at
                ON a.id = at.id_articles
                LEFT JOIN tags t
                ON at.id_tags = t.id
                WHERE t.id = '.(int)$idTag;
        }
        else {
            $sql = 'SELECT
                    a.id as idArticle,
                    a.title,
                    a.body,
                    t.id as idTags,
                    t.name
                FROM  articles a
                LEFT JOIN articles_tags at
                ON a.id = at.id_articles
                LEFT JOIN tags t
                ON at.id_tags = t.id';
        }


        $articles = $this->app['sql']->query($sql);
        $articles = $articles->fetchAll(PDO::FETCH_ASSOC);


        $articlesProcessed = array();
        foreach ($articles as $result) {
            $articlesProcessed[$result['idArticle']]['title'] = $result['title'];
            $articlesProcessed[$result['idArticle']]['body'] = $result['body'];
            $articlesProcessed[$result['idArticle']]['tags'][$result['idTags']] = $result['name'];
        }

        $this->data['articles'] = $articlesProcessed;

        return $this->app['twig']->render('home.twig', $this->data);
    }

}
