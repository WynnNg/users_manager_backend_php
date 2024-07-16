<?php
if (!defined('_CODE')) {
    die("Access denied");
}
$userId = filter()['id'];

if (!empty($userId)) {
    $user = oneRaw("SELECT * FROM users WHERE id = '$userId'");
    if (!empty($user)) {
        //Xóa login token
        $isDeleteToken = delete('tokenlogin', "user_id = '$userId'");
        if ($isDeleteToken) {
            $isDeleteUser = delete('users', "id = '$userId'");
            if ($isDeleteUser) {
                setFlashData('msg', 'Xóa người dùng thành công!');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Xóa người dùng thất bại. Vui lòng thử lại!');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Xóa người dùng thất bại. Vui lòng thử lại!');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Người dùng không có trong hệ thống');
        setFlashData('msg_type', 'danger');
    }
}

redirect('?modules=users&action=list');

?>
<h1>Delete</h1>