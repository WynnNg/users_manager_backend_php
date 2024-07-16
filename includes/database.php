<?php
if (!defined('_CODE')) {
    die("Access denied");
}

function query($sql, $data = [], $check = false)
{
    global $conn;
    $status = false;
    try {
        $statement = $conn->prepare($sql);

        if (!empty($data)) {
            $status = $statement->execute($data);
        } else {
            $status = $statement->execute();
        };
    } catch (Exception $e) {
        echo "Connection failed: " . $e->getMessage() . '<br>';
        echo "File: " . $e->getFile() . '<br>';
        echo "Line : " . $e->getLine() . '<br>';
        die();
    }

    if ($check) {
        return $statement;
    }

    return $status;
}

//Thêm dữ liệu
function insert($table, $data)
{
    $arrKeys = array_keys($data);
    $stringKeys = implode(',', $arrKeys);
    $stringValues = ':' . implode(',:', $arrKeys);

    $sql = 'INSERT INTO ' . $table . '(' . $stringKeys . ')' . ' VALUES(' . $stringValues . ')';

    return query($sql, $data);
}

//Cập nhập dữ liệu
function update($table, $data, $condition = '')
{
    $update = '';
    foreach ($data as $key => $value) {
        $update .= $key . '= :' . $key . ',';
    }

    $update = trim($update, ',');

    if (!empty($condition)) {
        $sql = 'UPDATE ' . $table . ' SET ' . $update . ' WHERE ' . $condition;
    } else {
        $sql = 'UPDATE ' . $table . ' SET ' . $update;
    }

    return query($sql, $data);
}

//Xóa dữ liệu
function delete($table, $condition = '')
{
    if (empty($condition)) {
        $sql = 'DELETE FROM ' . $table;
    } else {
        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $condition;
    }
    return query($sql);
}

//Lấy nhiều dòng dữ liệu
function getRaw($sql)
{
    $res = query($sql, '', true);
    if (is_object($res)) {
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }
}

//Lấy một dòng dữ liệu
function oneRaw($sql)
{
    $res = query($sql, '', true);
    if (is_object($res)) {
        return $res->fetch(PDO::FETCH_ASSOC);
    }
}

//Lấy số dòng dữ liệu
function countRows($sql)
{
    $res = query($sql, '', true);
    if (!empty($res)) {
        return $res->rowCount();
    }
}
