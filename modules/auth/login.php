<?php
if (!defined('_CODE')) {
    die("Access denied");
}

$data = [
    'pageTitle' => 'Đăng nhập'
];

if (isLogin()) {
    redirect('?modules=users&action=list');
}

if (isPost()) {
    $filterData = filter();

    if (!empty(trim($filterData['email'])) && !empty(trim($filterData['password']))) {
        $email = $filterData['email'];
        $password = $filterData['password'];

        $userQuery = oneRaw("SELECT password, id FROM users WHERE email = '$email'");

        if (!empty($userQuery)) {
            $passwordDb = $userQuery['password'];
            $userId = $userQuery['id'];
            if (password_verify($password, $passwordDb)) {

                //Tạo login token
                $loginToken = sha1(uniqid() . time());

                //Insert token vào table tokenLogin
                $dataInsert = [
                    "user_id" => $userId,
                    'token' => $loginToken,
                    'create_at' => date('Y-m-d H:i:s'),
                ];

                $status = insert('tokenlogin', $dataInsert);

                if ($status) {

                    //Lưu token vào session
                    setSession('loginToken', $loginToken);
                    redirect('?modules=users&action=list');
                }
            } else {
                setFlashData('msg', 'Email hoặc mật khẩu không chính xác');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Email hoặc mật khẩu không chính xác');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Bạn cần nhập email và mật khẩu');
        setFlashData('msg_type', 'danger');
    }
    redirect('?modules=auth&action=login');
}

layout('header_guest', $data);
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h4 class="text-center text-uppercase">Đăng nhập quản lý người dùng</h4>
        <?php
        if (!empty($msg)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form action='' method="post" style="display: flex; flex-direction: column; gap: 8px">
            <div class="form-group">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Email" />
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Password" />
            </div>

            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>

            <hr>
            <div>
                <p class="text-center" style="margin: 0;">
                    <a href="?modules=auth&action=forgot_pass" target="_blank">Quên mật khẩu</a>
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