<?php
if (!defined('_CODE')) {
    die("Access denied");
}

$data = [
    'pageTitle' => 'Cập nhập thông tin người dùng'
];

$userId = filter()['id'];

if (!empty($userId)) {
    $user = oneRaw("SELECT * FROM users WHERE id = '$userId'");
    if (!empty($user)) {
        setFlashData("userInfo", $user);
    } else {
        setFlashData('msg', 'Hệ thống đang gặp sự cố. Vui lòng thử lại!!!');
        setFlashData('msg_type', 'danger');
        redirect('?modules=users&action=list');
    }
}

if (isPost()) {

    $filterAll = filter();
    $error = [];

    //validate full name: 
    if (empty($filterAll['fullname'])) {
        $error['fullname']['required'] = 'Bạn cần nhập họ và tên';
    } else {
        if (strlen($filterAll['fullname']) < 5) {
            $error['fullname']['min'] = 'Họ và tên không được ít hơn 5 ký tự';
        }
    }

    //validate email: 
    if (empty($filterAll['email'])) {
        $error['email']['required'] = 'Bạn cần nhập email';
    } else {
        $email = $filterAll['email'];
        $sql = "SELECT id FROM users WHERE email = '$email' AND id <> $userId ";
        if (countRows($sql) > 0) {
            $error['email']['unique'] = 'Email đã tồn tại';
        }
    }

    //validate phone: 
    if (empty($filterAll['phone'])) {
        $error['phone']['required'] = 'Bạn cần nhập số điện thoại';
    } else {
        if (!isPhone($filterAll['phone'])) {
            $error['phone']['format'] = 'Số điện thoại không đúng định dạng';
        }
    }

    //validate password: 
    if (!empty($filterAll['password'])) {
        if (strlen($filterAll['password']) < 7) {
            $error['password']['min'] = 'Mật khẩu không được ít hơn 8 ký tự';
        }
        //validate password_confirm: 
        if (empty($filterAll['password_confirm'])) {
            $error['password_confirm']['required'] = 'Bạn cần nhập lại mật khẩu';
        } else {
            if ($filterAll['password_confirm'] != $filterAll['password']) {
                $error['password_confirm']['match'] = 'Mật khẩu không giống nhau';
            }
        }
    }

    if (empty($error)) {

        //xử lý update
        $data = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'status' => $filterAll['status'],
            'update_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($filterAll['password'])) {
            $data = [
                'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
            ];
        }

        $status = update('users', $data, "id = $userId");

        if ($status) {
            setFlashData('msg', 'Cập nhập thông tin người dùng thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố. Vui lòng thử lại sau!!!');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $error);
        setFlashData('olData', $filterAll);
    }
    redirect('?modules=users&action=edit&id=' . $userId);
}



layout('header_guest', $data);

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('olData');
$userData = getFlashData('userInfo');

if (!empty($userData)) {
    $oldData = $userData;
}

?>

<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h4 class="text-center text-uppercase">Cập nhập thông tin người dùng</h4>
        <?php
        if (!empty($msg)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form action='' method="post" style="display: flex; flex-direction: column; gap: 8px">
            <div class="form-group">
                <label for="">Họ và tên</label>
                <input name="fullname" type="text" class="form-control" placeholder="Họ và tên" value="<?php echo oldFormData('fullname', $oldData) ?>" />
                <?php
                echo formError('fullname', '<div class="error">',  '</div>', $errors);
                ?>
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Email" value="<?php echo oldFormData('email', $oldData) ?>" />
                <?php
                echo formError('email', '<div class="error">',  '</div>', $errors);
                ?>
            </div>
            <div class="form-group">
                <label for="">Số điện thoại</label>
                <input name="phone" type="number" class="form-control" placeholder="Số điện thoại" value="<?php echo oldFormData('phone', $oldData) ?>" />
                <?php
                echo formError('phone', '<div class="error">',  '</div>', $errors);
                ?>
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Không cần nhập password nếu không thay đổi" />
                <?php
                echo formError('password', '<div class="error">',  '</div>', $errors);
                ?>
            </div>
            <div class="form-group">
                <label for="">Nhập lại Password</label>
                <input name="password_confirm" type="password" class="form-control" placeholder="Nhập lại password" />
                <?php
                echo formError('password_confirm', '<div class="error">',  '</div>', $errors);
                ?>
            </div>
            <div class="form-group">
                <label for="">Trạng thái</label>
                <select name="status" id="" class="form-control">
                    <option value='0' <?php echo oldFormData('status', $oldData) == '0' ? 'selected' : '' ?>>Chưa kích hoạt</option>
                    <option value='1' <?php echo oldFormData('status', $oldData) == '1' ? 'selected' : '' ?>>Đã kích hoạt</option>
                </select>
            </div>
            <input type="hidden" name="id" value="<?php echo $userId ?>" />

            <button type="submit" class="btn btn-primary btn-block">Cập nhập thông tin người dùng</button>

            <hr>
            <div>
                <p class="text-center" style="margin: 0;">
                    <a href="?modules=users&action=list">Quay lại danh sách người dùng</a>
                </p>
            </div>
        </form>

    </div>
</div>

<?php
layout('footer_guest')

?>