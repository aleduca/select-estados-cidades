<?php

$queryData = [];

function read(string $table, string $fields = '*')
{
    global $queryData;

    if (isset($queryData['sql'])) {
        $queryData = [];
    }

    $queryData['table'] = $table;
    $queryData['read'] = true;

    $queryData['sql'] = "select {$fields} from {$table}";
}

function fieldWhere(string $field)
{
    if (str_contains($field, '.')) {
        [,$fieldWhere] = explode('.', $field);
    } else {
        $fieldWhere = $field;
    }

    return $fieldWhere;
}

function where()
{
    global $queryData;
    $numArgs = func_num_args();
    $getArgs = func_get_args();

    if (!isset($queryData['read'])) {
        throw new Exception("A função read é obrigatória");
    }

    if ($numArgs < 2 || $numArgs > 3) {
        throw new Exception("O where precisa de 2 ou 3 parâmetros");
    }

    if ($numArgs === 2) {
        $field = $getArgs[0];
        $operator = '=';
        $value = $getArgs[1];
    }

    if ($numArgs === 3) {
        $field = $getArgs[0];
        $operator =  $getArgs[1];
        $value = $getArgs[2];
    }

    $fieldWhere = fieldWhere($field);
    $queryData['execute'] = [$fieldWhere => $value];
    $queryData['where'] = true;

    $queryData['sql'] = "{$queryData['sql']} where {$field} {$operator} :{$fieldWhere}";
}

function orWhere()
{
    global $queryData;

    $numArgs = func_num_args();
    $getArgs = func_get_args();

    if (!isset($queryData['where'])) {
        throw new Exception('where obrigatório antes de chamar o andWhere');
    }

    if ($numArgs < 2 || $numArgs > 4) {
        throw new Exception("O where precisa de 2 até 4 parâmetros");
    }

    $data = match ($numArgs) {
        2 => whereTwoParameter($getArgs),
        3 => whereTwoParameter($getArgs),
        4 => $getArgs
    };

    [$field, $operator, $value, $typeWhere] = $data;

    $queryData['sql'] = "{$queryData['sql']} {$typeWhere} {$field} {$operator} :{$field}";
    $queryData['execute'] = array_merge($queryData['execute'], [$field => $value]);
}


function whereTwoParameter($getArgs)
{
    $field = $getArgs[0];
    $operator = '=';
    $value = $getArgs[1];
    $typeWhere = 'or';

    return [$field, $operator, $value, $typeWhere];
}

function whereThreeParameter($getArgs)
{
    $acceptedOperators = ['>','<', '=','!=','!==','<=','>='];
    $field = $getArgs[0];
    $operator = in_array($getArgs[1], $acceptedOperators) ? $getArgs[1] : '=';
    $value = in_array($getArgs[1], $acceptedOperators) ? $getArgs[2] : $getArgs[1];
    $typeWhere = $getArgs[2] === 'and' ? 'and' : 'or';

    return [$field, $operator, $value, $typeWhere];
}

function limit(int|string $limit = 10)
{
    global $queryData;

    if (!isset($queryData['read'])) {
        throw new Exception("A função read é obrigatória");
    }

    $queryData['limit'] = true;

    $queryData['sql'] = "{$queryData['sql']} limit $limit";
}

function paginate(int|string $perPage = 10)
{
    global $queryData;

    if (isset($queryData['limit'])) {
        throw new Exception("Antes de fazer a paginação remova a chamada da função limit");
    }

    $rowCount = execute(rowCount:true);
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);

    $page = $page ?? 1;
    $queryData['currentPage'] = $page;
    // 40/10 = 4
    $queryData['pageCount'] = (int)ceil($rowCount / $perPage);
    $queryData['paginate'] = true;

    // 4 - 1 = 3 * 10 = 30
    $offset = ($page - 1) * $perPage;
    $queryData['sql'] = "{$queryData['sql']} limit {$perPage} offset {$offset}";
}

function search(array $search)
{
    global $queryData;

    if (!isAssociativeArray($search)) {
        throw new Exception('A busca tem que ser um array associativo');
    }

    if (isset($queryData['where'])) {
        throw new Exception('A busca não pode ter a chamaa da função where');
    }

    $sql = "{$queryData['sql']} where";

    foreach ($search as $field => $searched) {
        $sql.=" {$field} like :{$field} or";
        $execute[$field] = "%{$searched}%";
    }

    $sql = trim($sql, " or");

    $queryData['sql'] = $sql;
    $queryData['execute'] = $execute;
    $queryData['search'] = filter_input(INPUT_GET, 's', FILTER_SANITIZE_STRING);
}

function render()
{
    global $queryData;

    parse_str(preg_replace('/(\?|\&)?page=\d+(\&|\?)?/', '', QUERY_STRING), $output);

    $total = $queryData['pageCount'];

    $links = '<ul class="pagination">';
    if ($queryData['currentPage'] > 1) {
        $previous = $queryData['currentPage'] - 1;
        $linkPage = http_build_query(array_merge($output, ['page' => $previous]));
        $links.="<li class='page-item'><a href='?{$linkPage}' class='page-link'>Anterior</a></li>";
    }

    $class = '';
    for ($i = 1; $i<=$total; $i++) {
        $class = ($queryData['currentPage'] === $i) ? 'active' : '';
        $linkPage = http_build_query(array_merge($output, ['page' => $i]));
        $links.="<li class='page-item ${class}'><a href='?{$linkPage}' class='page-link'>{$i}</a></li>";
    }

    if ($queryData['currentPage'] < $total) {
        $next = $queryData['currentPage'] + 1;
        $linkPage = http_build_query(array_merge($output, ['page' => $next]));
        $links.="<li class='page-item'><a href='?{$linkPage}' class='page-link'>Próxima</a></li>";
    }
    $links .= '</ul>';

    return $links;
}

function order(string $by, string $descOrAsc = 'asc')
{
    global $queryData;

    if (isset($queryData['paginate'])) {
        throw new Exception("O order não pode vir depois da paginação");
    }

    $queryData['sql'] = "{$queryData['sql']} order by {$by} {$descOrAsc}";
}


function tableToJoin(string $table, string $parentFieldToJoin)
{
    $inflector = \Doctrine\Inflector\InflectorFactory::create()->build();

    $tableSingular = $inflector->singularize($table);
    return $tableSingular.ucfirst($parentFieldToJoin);
}


function tableJoin(string $table, string $parentFieldToJoin, string $typeJoin = "inner")
{
    global $queryData;

    if (isset($queryData['where'])) {
        throw new Exception("O where tem que vir depois do join");
    }

    $tableToJoin =  tableToJoin($queryData['table'], $parentFieldToJoin);
    $queryData['sql'] = "{$queryData['sql']} {$typeJoin} join {$table} on {$table}.{$tableToJoin} = {$queryData['table']}.{$parentFieldToJoin}";
    // dd($queryData);
}


function tableJoinWithFK(string $table, string $parentFieldToJoin, string $typeJoin = "inner")
{
    global $queryData;

    if (isset($queryData['where'])) {
        throw new Exception("O where tem que vir depois do join");
    }

    $tableToJoin =  tableToJoin($table, $parentFieldToJoin);
    $queryData['sql'] = "{$queryData['sql']} {$typeJoin} join {$table} on {$table}.{$parentFieldToJoin} = {$queryData['table']}.{$tableToJoin}";
    // dd($queryData);
}

function execute(bool $isfetchAll = true, bool $rowCount = false)
{
    global $queryData;

    if (!isset($queryData['sql'])) {
        throw new Exception("O sql é obrigatório");
    }

    // dd($queryData);
    // var_dump($queryData);

    try {
        $connect = connect();

        $prepare = $connect->prepare($queryData['sql']);
        $prepare->execute($queryData['execute'] ?? []);

        if ($rowCount) {
            return $prepare->rowCount();
        }

        return ($isfetchAll) ? $prepare->fetchAll() : $prepare->fetch();
    } catch (Exception $pdo) {
        dd($pdo->getMessage(), $pdo->getFile(), $pdo->getLine(), $queryData['sql']);
    }
}

// jeito sem usar a montagem de queries
function all($table, $fields = '*')
{
    try {
        $connect = connect();

        $prepare = $connect->query("select {$fields} from {$table}");
        return $prepare->fetchAll();
    } catch (PDOException $e) {
        var_dump($e->getMessage());
    }
}

function findBy($field, $value, $table, $fields = '*')
{
    try {
        $connect = connect();

        $prepare = $connect->prepare("select {$fields} from {$table} where {$field} = :{$field}");
        $prepare->execute([
            $field => $value
        ]);
        return $prepare->fetch();
    } catch (PDOException $e) {
        var_dump($e->getMessage());
    }
}
