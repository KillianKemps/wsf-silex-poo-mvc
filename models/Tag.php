<?php

use Blog\Model;

Class Tag extends Model
{

    public function getAll()
    {
        $sql = 'SELECT *
                FROM tags';

        $tags = $this->sql->query($sql);
        return $tags->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagsByArticle($idArticle)
    {
        $sql = 'SELECT *
                FROM tags, articles_tags
                WHERE tags.id = articles_tags.id_tags
                AND articles_tags.id_tags = :id';

        $arguments = array(
            ':id' => $idArticle,
        );

        $tags = $this->sql->prepareExec($sql, $arguments);
        return $tags->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($tag)
    {
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

        $this->sql->prepareExec($sql, $arguments);
    }
}
