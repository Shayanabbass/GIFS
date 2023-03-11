<?php
include('includes/application_top2.php');
/*if ($_POST['action'] == 'login_ajax') {
    $result = array('status' => false);
    if ($u->users_check2($_POST["user_name"], $_POST["user_password"], false) == true) {
        $result = array('status' => true);
    }
    echo(json_encode($result));
    die;
}*/


if ($_REQUEST["action"] == "ajax_login") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    // $_SESSION["users_name_temp"] = $email;
    // $_SESSION["users_password_temp"] = $password;
    $success = $u->users_check2($email, $password, false);
    echo json_encode(array(
        'status' => $success? 'success' : 'failure',
        "message"=>$success ? 'successmessage' : 'failure message'));
    die;
}

if (($_SESSION["users_id"] != "0") && isset($_SESSION["users_id"]))
    redirect('index.php');


if ($_GET["action"] == "login") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $_SESSION["users_name_temp"] = $email;
    $_SESSION["users_password_temp"] = $password;
    $u->users_check2();
}
$page_name = str_replace("-", "_", page_name());
if (function_exists($page_name)) {
    eval("\$data = " . $page_name . "();");
}
else
{
    $data["html_title"] = "The Baron Hotel Karbala | Login";
    $data["html_meta_description"] = "Login at Baron Hotel Karbala.";
    $data["html_meta_keywords"] = "Hotels, contact, bookings, tour, Karbala, Iraq, Ziyarat, Packages, travel, 2020";
}
$class_body = 'home page-template-default page page-id-2039 gdlr-core-body tourmaster-body woocommerce-no-js traveltour-body traveltour-body-front traveltour-full  traveltour-with-sticky-navigation gdlr-core-link-to-lightbox';
?>
<!DOCTYPE html>
<html lang="en-US" prefix="og: http://ogp.me/ns#" class="no-js">

<head>
    <title><?php echo $data["html_title"]; ?></title>
    <meta name="keywords" content="<?php echo $data["html_meta_keywords"]; ?>"/>
    <meta name="description" content="<?php echo $data["html_meta_description"]; ?>"/>
    <?php include("template_parts/head.php"); ?>
    <?php echo $data["html_head"]; ?>
</head>

<body class="<?php echo $class_body; ?>">
<?php include("template_parts/header_mobile.php"); ?>

<div class="traveltour-body-outer-wrapper ">
    <div class="traveltour-body-wrapper clearfix  traveltour-with-transparent-header traveltour-with-frame">
        <?php include("template_parts/header.php"); ?>

        <div class="traveltour-page-title-wrap  traveltour-style-custom traveltour-left-align">
            <div class="traveltour-header-transparent-substitute" style="height: 147px;"></div>
            <div class="traveltour-page-title-overlay"></div>
            <div class="traveltour-page-title-container traveltour-container">
                <div class="traveltour-page-title-content traveltour-item-pdlr">
                    <h1 class="traveltour-page-title">Members Area</h1></div>
            </div>
        </div>


        <div class="traveltour-page-wrapper" id="traveltour-page-wrapper">
            <div class="tourmaster-template-wrapper">
                <div class="tourmaster-container">
                    <div class="tourmaster-page-content tourmaster-login-template-content  tourmaster-item-pdlr">

                        <?php

                        if($_SESSION['error'] == true) {
                            echo '
                        <div class="tourmaster-notification-box tourmaster-failure">'.$_SESSION['error_message'].'</div>';
                        }
                        list($authUrl, $loginUrl) = get_login_links();

                        ?>


                        <form class="tourmaster-login-form tourmaster-form-field tourmaster-with-border" method="post"
                              action="login.php?action=login">
                            <div class="tourmaster-login-form-fields clearfix"><p class="tourmaster-login-user"><label>Username</label>
                                    <input type="text" name="email"></p>
                                <p class="tourmaster-login-pass"><label>Password</label> <input type="password"
                                                                                                name="password"></p></div>
                            <p class="tourmaster-login-submit"><input type="submit" name="wp-submit"
                                                                      class="tourmaster-button" value="Sign In!"></p>
                            <p class="tourmaster-login-lost-password"><a
                                        href="https://demo.goodlayers.com/traveltour/my-account/lost-password/?source=tm">Forget
                                    Password?</a></p><input type="hidden" name="rememberme" value="forever"> <input
                                    type="hidden" name="redirect_to" value="/traveltour/login/?status=login_incorrect">
                            <input type="hidden" name="source" value="tm">
                            <div id="nsl-custom-login-form-3">
                                <div class="nsl-container nsl-container-block nsl-container-embedded-login-layout-below"
                                     style="display: block;"><a
                                            href="<?php echo $loginUrl; ?>"
                                            rel="nofollow" aria-label="Continue with <b>Facebook</b>" data-plugin="nsl"
                                            data-action="connect" data-provider="facebook" data-popupwidth="475"
                                            data-popupheight="175"><span
                                                class="nsl-button nsl-button-default nsl-button-facebook"
                                                style="background-color:#4267b2;"><span
                                                    class="nsl-button-svg-container"><svg
                                                        xmlns="http://www.w3.org/2000/svg"><path fill="#fff"
                                                                                                 d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"></path></svg></span><span
                                                    class="nsl-button-label-container">Continue with <b>Facebook</b></span></span></a><a
                                            href="<?php echo $authUrl; ?>"
                                            rel="nofollow" aria-label="Continue with <b>Google</b>" data-plugin="nsl"
                                            data-action="connect" data-provider="google" data-popupwidth="600"
                                            data-popupheight="600"><span
                                                class="nsl-button nsl-button-default nsl-button-google"
                                                data-skin="uniform" style="background-color:#dc4e41;"><span
                                                    class="nsl-button-svg-container"><svg
                                                        xmlns="http://www.w3.org/2000/svg"><path fill="#fff"
                                                                                                 d="M7.636 11.545v2.619h4.331c-.174 1.123-1.309 3.294-4.33 3.294-2.608 0-4.735-2.16-4.735-4.822 0-2.661 2.127-4.821 4.734-4.821 1.484 0 2.477.632 3.044 1.178l2.073-1.997C11.422 5.753 9.698 5 7.636 5A7.63 7.63 0 0 0 0 12.636a7.63 7.63 0 0 0 7.636 7.637c4.408 0 7.331-3.098 7.331-7.462 0-.502-.054-.884-.12-1.266h-7.21zm16.364 0h-2.182V9.364h-2.182v2.181h-2.181v2.182h2.181v2.182h2.182v-2.182H24"></path></svg></span><span
                                                    class="nsl-button-label-container">Continue with <b>Google</b></span></span></a>
                                </div>
                            </div>
                        </form>


                        <div class="tourmaster-login-bottom"><h3 class="tourmaster-login-bottom-title">Do not have an
                                account?</h3>
                            <a class="tourmaster-login-bottom-link"
                               href="register">Create an Account</a></div>
                        </div>
                </div>
            </div>
        </div>


        <?php include("template_parts/footer.php"); ?>

    </div>
</div>
<a href="#traveltour-top-anchor" class="traveltour-footer-back-to-top-button" id="traveltour-footer-back-to-top-button"><i
            class="fa fa-angle-up"></i></a>
<?php include("template_parts/footer_scripts.php"); ?>

</body>
</html>
