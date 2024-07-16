<?php
if (!defined('_CODE')) {
    die("Access denied");
}

if (isLogin()) {
    $token = getSession('loginToken');
    echo $token;
    $status = delete('tokenlogin', "token = '$token'");
    if ($status) {
        removeSession('loginToken');
        redirect("?modules=auth&action=login");
    }
}

?>

<h1>Log out</h1>