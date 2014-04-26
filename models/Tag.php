<?php

use Blog\Model;

Class Tag extends Model
{

    public function getAll()
    {
        $sql = 'SELECT *
                FROM tag';

        $tags = $this->sql->query($sql);
        return $tags->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagsByArticle($idArticle)
    {
        $sql = 'SELECT *
                FROM tag, articles_tag
                WHERE tag.id = articles_tag.id_tag
                AND articles_tag.id_tag = :id';

        $arguments = array(
            ':id' => $idArticle,
        );

        $tags = $this->sql->prepareExec($sql, $arguments);
        return $tags->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($tag)
    {
        $sql = "INSERT INTO tag (
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
