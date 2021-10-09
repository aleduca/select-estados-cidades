<?php

function delete(string $table, array $where)
{
    try {
        if (!isAssociativeArray($where)) {
            throw new Exception("Array tem que ser associativo para deletar");
        }

        $connect = connect();

        $whereField = array_keys($where);

        $sql = "delete from {$table} where ";
        $sql.= "{$whereField[0]} = :{$whereField[0]}";

        $prepare = $connect->prepare($sql);
        $prepare->execute($where);
        return $prepare->rowCount();
    } catch (PDOException $e) {
        var_dump($e->getMessage());
    }
}
