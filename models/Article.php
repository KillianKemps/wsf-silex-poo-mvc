<?php

use Blog\Model;

Class Article extends Model
{
    public function getAllArticles($idTag = null)
    {
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
                WHERE t.id = '.(int)$idTag.'
                ORDER BY idArticle';
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
                ON at.id_tags = t.id
                ORDER BY idArticle';
        }


        $articles = $this->sql->query($sql);
        $articles = $articles->fetchAll(PDO::FETCH_ASSOC);


        $articlesProcessed = array();
        foreach ($articles as $result) {
            $articlesProcessed[$result['idArticle']]['title'] = $result['title'];
            $articlesProcessed[$result['idArticle']]['body'] = $result['body'];
            $articlesProcessed[$result['idArticle']]['tags'][$result['idTags']] = $result['name'];
        }

        return $articlesProcessed;
    }
}
