<?php
if (!defined('_CODE')) {
    die("Access denied");
}

$data = [
    'pageTitle' => 'Trang Dashboard'
];

layout('header', $data);

//Kiểm tra trạng thái đăng nhập

if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

?>

<h1>DASHBOARD</h1>

<?php
layout('footer')
?>