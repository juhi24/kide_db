<?php
function pdo_select($selectsql) {
    $yhteys = yhdista();
    try {
        $query = $yhteys->prepare($selectsql);
        $query->execute();
    } catch (PDOException $e) {
        pdo_error($e);
    }
    return $query;
}

function fetchAll_with_headers($query) {
    $arr = array();
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        if (empty($arr)) {
            $arr[0] = array_keys($row);
        }
        $arr[] = $row;
    }
    return $arr;
}

//log pdo errors and show error messages
function pdo_error(PDOException $e) {
    file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die('ERROR: ' . $e->getMessage());
}
?>
