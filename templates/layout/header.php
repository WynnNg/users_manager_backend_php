<?php
if (!defined('_CODE')) {
    die("Access denied");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['pageTitle'] ? $data['pageTitle'] : 'Quản lý người dùng' ?></title>
    <link rel="stylesheet" href=<?php echo _WEB_HOST_TEMPLATES . "/css/bootstrap.min.css" ?>>
    <link rel="stylesheet" href=<?php echo _WEB_HOST_TEMPLATES . "/css/style.css" ?>>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

</body>

</html>
<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap"></use>
                </svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="?modules=users&action=list" class="nav-link px-2 link-secondary">Overview</a></li>

            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
            </form>

            <div class="text-end">
                <a href="?modules=auth&action=logout" class="d-block link-dark text-decoration-none " aria-expanded="false">
                    <i class="fa-regular fa-square-caret-up"></i>
                    Đăng xuất
                </a>
                <!-- <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1" style="">
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="?modules=auth&action=logout">Đăng xuất</a></li>
                </ul> -->
            </div>
        </div>
    </div>
</header>