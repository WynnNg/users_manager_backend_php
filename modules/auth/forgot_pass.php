<?php
if (!defined('_CODE')) {
    die("Access denied");
}

$data = [
    'pageTitle' => 'Quên mật khẩu'
];

if (isLogin()) {
    redirect('?modules=users&action=list');
}

if (isPost()) {
    $filterData = filter();

    if (!empty(trim($filterData['email']))) {
        $email = $filterData['email'];

        $userQuery = oneRaw("SELECT id FROM users WHERE email = '$email'");

        if (!empty($userQuery)) {
            $userId = $userQuery['id'];


            //Tạo forgot token
            $forgotToken = sha1(uniqid() . time());

            //update token vào table tokenLogin
            $dataUpdate = [
                'forgotToken' => $forgotToken,
            ];

            $statusUpdate = update('users', $dataUpdate, "id = $userId");

            if ($statusUpdate) {

                $linkReset = _WEB_HOST . '?modules=auth&action=reset&token=' . $forgotToken;

                //Gửi mail
                $subject = "Yêu cầu thay đổi mật khẩu";
                $content = 'Xin chào bạn!' . '<br>';
                $content .= 'Bạn vui lòng click vào đường link dưới đây để thay đổi mật khẩu mới:' . '<br>';
                $content .= $linkReset . '<br>';
                $content .= 'Xin chân thành cảm ơn!' . '<br>';

                $statusMail = sendMail($email, $subject, $content);

                if ($statusMail) {
                    setFlashData('msg', 'Vui lòng kiểm tra mail để xem hướng dẫn thay đổi mật khẩu');
                    setFlashData('msg_type', 'success');
                } else {
                    setFlashData('msg', 'Lỗi hệ thống! Vui lòng thử lại sau!(mail)');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Lỗi hệ thống! Vui lòng thử lại sau!');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Email không tồn tại trong hệ thống!');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Bạn cần nhập email để lấy lại mật khẩu');
        setFlashData('msg_type', 'danger');
    }
    redirect('?modules=auth&action=forgot_pass');
}

layout('header_guest', $data);
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

?>

<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h4 class="text-center text-uppercase">Quên mật khẩu</h4>
        <?php
        if (!empty($msg)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form action='' method="post" style="display: flex; flex-direction: column; gap: 8px">
            <label for="">Nhập email để lấy lại mật khẩu</label>
            <div class="form-group">
                <input name="email" type="email" class="form-control" placeholder="Email" />
            </div>
            <button type="submit" class="btn btn-primary btn-block">Lấy mật khẩu</button>

            <hr>
            <div>
                <p class="text-center" style="margin: 0;">
                    <a href="?modules=auth&action=login" target="_blank">Đăng nhập</a>
                </p>
                <p class="text-center" style="margin: 0;">
                    <a href="?modules=auth&action=register">Đăng ký tài khoản</a>
                </p>
            </div>
        </form>

    </div>
</div>

<?php
layout('footer_guest')

?>