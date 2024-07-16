<?php
if (!defined('_CODE')) {
    die("Access denied");
}

layout('header_guest');

$token = filter()['token'];

if (!empty($token)) {
    $query = oneRaw("SELECT id from users WHERE activeToken = '$token'");
    if (!empty($query)) {
        $userId = $query['id'];
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null,
        ];
        $status = update('users', $dataUpdate, 'id = ' . $userId);
        if ($status) {
            setFlashData('msg', 'kích hoạt thành công! Bạn có thể đăng nhập ngay bây giờ.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'kích hoạt không thành công! Vui lòng liên hệ quản trị viên.');
            setFlashData('msg_type', 'danger');
        }

        redirect('?modules=auth&action=login');
    } else {
        getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
} else {
    getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
}

?>

<?php
layout('footer_guest');

?>