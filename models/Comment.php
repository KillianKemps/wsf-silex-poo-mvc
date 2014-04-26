<?php

use Blog\Model;

Class Comment extends Model
{

    public function getAll()
    {
        $sql = 'SELECT *
                FROM comment';

        $comments = $this->sql->query($sql);
        return $tags->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommentsByArticle($idArticle)
    {
        $sql = 'SELECT *
                FROM comment, articles
                WHERE comments.id_article = :id';

        $arguments = array(
            ':id' => $idArticle,
        );

        $comments = $this->sql->prepareExec($sql, $arguments);
        return $tags->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($comment, $idArticle)
    {
        $sql = "INSERT INTO comment (
                id,
                id_articles,
                comment
            )
            VALUES (
                NULL ,
                :idArticle,
                :comment
            )";

        $arguments = array(
            ':idArticle' => $idArticle,
            ':comment' => $comment
        );

        $this->sql->prepareExec($sql, $arguments);
    }
}
