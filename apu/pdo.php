<?php
function pdo_query($selectsql) {
    $yhteys = connect();
    try {
        $query = $yhteys->prepare($selectsql);
        $query->execute();
    } catch (PDOException $e) {
        pdo_error($e);
    }
    return $query;
}

function fetchAll($query) {
    $arr = array();
    while ($row = $query->fetch()) {
        $arr[] = $row;
    }
    return $arr;
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
    file_put_contents('/home/jussitii/log/PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    die('ERROR: ' . $e->getMessage());
}

function getHabits() {
    return fetchAll(pdo_query('SELECT * FROM habits ORDER BY id'));
}

function getSites() {
    return fetchAll(pdo_query('SELECT * FROM ARM_site ORDER BY id'));
}
?>
