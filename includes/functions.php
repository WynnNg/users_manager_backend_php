<?php
if (!defined('_CODE')) {
    die("Access denied");
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layout($section = 'header', $data = [])
{
    if (file_exists(_WEB_PATH_TEMPLATES . '/layout/' . $section . '.php')) {
        require_once _WEB_PATH_TEMPLATES . '/layout/' . $section . '.php';
    }
}

function sendMail($to, $subject, $content)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->CharSet = "UTF-8";
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = _MAIL_USER;                     //SMTP username
        $mail->Password   = _MAIL_PASS;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('xanhttg@gmail.com', 'xanh');
        $mail->addAddress($to);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        //PHPMailer SSL Certificate verify failed
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            )
        );

        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Kiểm tra phương thức get
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }

    return false;
}

// Kiểm tra phương thức post
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }

    return false;
}

// Hàm filter dữ liệu

function filter()
{
    $filterArr = [];
    if (isGet()) {
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            };
        }
    }

    if (isPost()) {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            };
        }
    }

    return $filterArr;
}

//Kiểm tra email
function isEmail($email)
{
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

//Kiểm tra số nguyên
function isNumberInt($number)
{
    $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    return $checkNumber;
}

//Kiểm tra số thực
function isNumberFloat($number)
{
    $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    return $checkNumber;
}

//Kiểm tra số điện thoại
function isPhone($phone)
{

    //Kiểm tra số 0 đầu tiên;
    $checkZero = false;
    if ($phone[0] == '0') {
        $checkZero = true;
        $phone = substr($phone, 1);
    }

    //Kiểm tra 9 chữ số
    $isNumberInt = false;
    if (strlen($phone) == 9 && isNumberInt($phone)) {
        $isNumberInt = true;
    }

    return $checkZero && $isNumberInt;
}

//Thông báo
function getMsg($smg, $type = 'success')
{
    echo '<div class="alert alert-' . $type . '">';
    echo $smg;
    echo "</div>";
}

//Hàm chuyển hướng
function redirect($path = 'index.php')
{
    header("Location: $path");
    exit;
}

//Thông báo lỗi form
function formError($fieldName, $beforeHTML, $afterHTML, $errors)
{

    return (!empty($errors[$fieldName])) ? $beforeHTML . reset($errors[$fieldName]) . $afterHTML : null;
}

//Hiển thị dữ liệu cũ của form
function oldFormData($fieldName, $oldData)
{
    return (!empty($oldData[$fieldName])) ? $oldData[$fieldName] : null;
}

//Kiểm tra trạng thái login
function isLogin()
{
    $isLogin = false;
    if (!empty(getSession('loginToken'))) {
        $token = getSession('loginToken');

        $query = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$token'");
        if (!empty($query)) {
            $isLogin = true;
        } else {
            removeSession('loginToken');
        }
    }
    return $isLogin;
}
