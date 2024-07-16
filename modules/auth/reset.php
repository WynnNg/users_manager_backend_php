<?php
if (!defined('_CODE')) {
    die("Access denied");
}
layout('header_guest');

$token = filter()['token'];

if (!empty($token)) {
    $query = oneRaw("SELECT id FROM users WHERE forgotToken = '$token'");
    if (!empty($query)) {
        $userId = $query['id'];

        if (isPost()) {
            $filterAll = filter();
            $error = [];

            //validate password: 
            if (empty($filterAll['password'])) {
                $error['password']['required'] = 'Bạn cần nhập mật khẩu';
            } else {
                if (strlen($filterAll['password']) < 7) {
                    $error['password']['min'] = 'Mật khẩu không được ít hơn 8 ký tự';
                }
            }

            //validate password_confirm: 
            if (empty($filterAll['password_confirm'])) {
                $error['password_confirm']['required'] = 'Bạn cần nhập lại mật khẩu';
            } else {
                if ($filterAll['password_confirm'] != $filterAll['password']) {
                    $error['password_confirm']['match'] = 'Mật khẩu không giống nhau';
                }
            }

            if (empty($error)) {

                //xử lý thay đổi mật khẩu
                $dataUpdate = [
                    'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
                    'forgotToken' => null,
                    'update_at' => date('Y-m-d H:i:s'),
                ];

                $statusUpdate = update('users', $dataUpdate, "id = $userId");

                if ($statusUpdate) {
                    setFlashData('msg', 'Thay đổi mật khẩu thành công!');
                    setFlashData('msg_type', 'success');
                    redirect('?modules=auth&action=login');
                } else {
                    setFlashData('msg', 'Hệ thống đang gặp sự cố. Vui lòng thử lại sau!!!');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu');
                setFlashData('msg_type', 'danger');
                setFlashData('errors', $error);
                redirect('?modules=auth&action=reset&token=' . $token);
            }
        }
    } else {
        getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
} else {
    getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
?>
<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h4 class="text-center text-uppercase">Thay đổi mật khẩu</h4>
        <?php
        if (!empty($msg)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form action='' method="post" style="display: flex; flex-direction: column; gap: 8px">
            <div class="form-group">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Password" />
                <?php
                echo formError('password', '<div class="error">',  '</div>', $errors);
                ?>
            </div>
            <div class="form-group">
                <label for="">Nhập lại Password</label>
                <input name="password_confirm" type="password" class="form-control" placeholder="Nhập lại Password" />
                <?php
                echo formError('password_confirm', '<div class="error">',  '</div>', $errors);
                ?>
            </div>
            <input type="hidden" name="token" value="<?php echo $token; ?>" />

            <button type="submit" class="btn btn-primary btn-block">Thay đổi mật khẩu</button>

            <hr>
            <div>
                <p class="text-center" style="margin: 0;">
                    <a href="?modules=auth&action=login">Đăng nhập</a>
                </p>
            </div>
        </form>

    </div>
</div>
<?php


layout('footer_guest')

?>