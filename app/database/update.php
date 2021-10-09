<?php

function update(string $table, array $fields, array $where)
{
    try {
        $connect = connect();

        $sql = "update {$table} set ";
        foreach (array_keys($fields) as $field) {
            $sql.= "{$field}=:{$field},";
        }

        $sql = trim($sql, ',');

        $whereField = array_keys($where);

        $sql.=" where {$whereField[0]} = :{$whereField[0]}";

        $prepare = $connect->prepare($sql);
        $prepare->execute(array_merge($fields, $where));
        return $prepare->rowCount();
    } catch (PDOException $e) {
        var_dump($e->getMessage());
    }
}
