<?php
if (!defined('_CODE')) {
    die("Access denied");
}
$data = [
    'pageTitle' => 'Danh sách người dùng'
];

layout('header', $data);

//Kiểm tra trạng thái đăng nhập

if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

// Truy vấn vào bảng users
$listUsers = getRaw("SELECT * FROM users ORDER BY update_at");

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<div class="m-5">
    <h3 class="text-center">Quản lý người dùng</h3>
    <p>
        <a href="?modules=users&action=add" class="btn btn-sm btn-success"><i class="fa-solid fa-plus"></i>&nbsp;Thêm người dùng</a>
    </p>
    <?php
    if (!empty($msg)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <table class="table table-sm table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">STT</th>
                <th scope="col">Họ và Tên</th>
                <th scope="col">Email</th>
                <th scope="col">Số điện thoại</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Sửa</th>
                <th scope="col">Xóa</th>
            </tr>
        </thead>
        <tbody>

            <?php

            if (!empty($listUsers)) {
                $count = 0;
                foreach ($listUsers as $item) {
                    $count++;
            ?>
                    <tr>
                        <th scope="row"><?php echo  $count ?></th>
                        <td><?php echo  $item['fullname'] ?></td>
                        <td><?php echo  $item['email'] ?></td>
                        <td><?php echo  $item['phone'] ?></td>
                        <td><?php echo  $item['status'] == 1 ? "<button class='btn btn-success btn-sm'>Đã kích hoạt</button>" : "<button class='btn btn-secondary btn-sm'>Chưa kích hoạt</button>" ?></td>
                        <td><a href="<?php echo _WEB_HOST . '?modules=users&action=edit&id=' . $item['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen-to-square"></i></a></td>
                        <td><a href="<?php echo _WEB_HOST . '?modules=users&action=delete&id=' . $item['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></a></td>
                    </tr>
                <?php
                }
            } else {
                ?>
                <tr>
                    <td class="text-center" colspan="7">Không có người dùng nào</td>
                </tr>
            <?php
            }
            ?>

        </tbody>
    </table>
</div>

<?php
layout('footer')
?>