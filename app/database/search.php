<?php

/**
 * @throws Exception
 */
function searchTeste(string $table, array $search, string $fields = '*')
{
    try {
        if (!isAssociativeArray($search)) {
            throw new Exception('A busca tem que ser um array');
        }

        $connect = connect();

        $sql = "select {$fields} from {$table} where";
        foreach ($search as $field => $searched) {
            $sql.=" {$field} like :{$field} or";
            $execute[$field] = "%{$searched}%";
        }

        $sql = trim($sql, " or");

        $prepare = $connect->prepare($sql);
        $prepare->execute($execute ?? []);

        return $prepare->fetchAll();
    } catch (PDOException $e) {
        var_dump($e->getMessage());
    }
}
