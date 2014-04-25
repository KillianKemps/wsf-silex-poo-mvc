<?php

use Blog\Model;

Class Article extends Model
{
    public function getAllArticles($idTag = null)
    {
        if ($idTag) {
            $sql = 'SELECT
                       articles.id as articlesId,
                       title,
                       body,
                       tag.id as tagId,
                       tag.name
                   FROM articles
                   LEFT JOIN articles_tag
                   ON articles.id = articles_tag.id_articles
                   LEFT JOIN tag
                   ON articles_tag.id_tag = tag.id
                   WHERE tag.id = '.(int)$idTag.'
                   ORDER BY articlesId';
        }
        else {
            $sql = 'SELECT
                       articles.id as articlesId,
                       title,
                       body,
                       tag.id as tagId,
                       tag.name,
                       comment.id as commentId,
                       comment.comment as commentContent
                   FROM articles
                   LEFT JOIN articles_tag
                   ON articles.id = articles_tag.id_articles
                   LEFT JOIN tag
                   ON articles_tag.id_tag = tag.id
                   LEFT JOIN comment
                   ON articles.id = comment.id_articles
                   ORDER BY articlesId';
        }


        $articles = $this->sql->query($sql);
        $articles = $articles->fetchAll(PDO::FETCH_ASSOC);


        $articlesProcessed = array();
        foreach ($articles as $result) {
            $articlesProcessed[$result['articlesId']]['title'] = $result['title'];
            $articlesProcessed[$result['articlesId']]['body'] = $result['body'];
            $articlesProcessed[$result['articlesId']]['tags'][$result['tagId']] = $result['name'];
            $articlesProcessed[$result['articlesId']]['comments'][$result['commentId']] = $result['commentContent'];
        }

        return $articlesProcessed;
    }

    public function create($title, $article)
    {
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

        $this->sql->prepareExec($sql, $arguments);

        return $this->sql->lastId();
    }

    public function addTag($idArticle, $idTag)
    {
        $sql = "INSERT INTO articles_tags (
                    id ,
                    id_articles ,
                    id_tags
                )
                VALUES (
                    NULL ,
                    :idArticle,
                    :idTag
                )";

        $arguments = array(
            ':idArticle' => $idArticle,
            ':idTag' => $idTag,
        );

        $this->sql->prepareExec($sql, $arguments);
    }

    public function getArticle($id)
    {
        $sql = 'SELECT *
                FROM articles
                WHERE id = :id';
        $arguments = array(
            ':id' => $id,
        );

        $tags = $this->sql->prepareExec($sql, $arguments);
        return $tags->fetch(PDO::FETCH_ASSOC);
    }
}
