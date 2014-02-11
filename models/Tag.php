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
