<?php
function select_count($sql)
{
    $db = new db2();
    $a = $db->result($sql, 1);
    //$a = result;
    return $a["total"];
}

function index()
{
  $action = $_GET['ajax_action'];
  if($action == '')
  {
    
    return '';
  }
    $account_heads = select_count("select count(*) as total from account_heads");
    $transactions = select_count("select count(*) as total from transactions");
    $vendor = select_count("select count(*) as total from vendor");

    $issues_till_date = select_count("select count(*) as total from incident");
    $issues_solved = select_count("select count(*) as total from incident where incident_status_id = 2");
    //$transactions = select_count("select count(*) as total from transactions");
    $temp = '<div class="col-md-12">
	
	
	<div class="row">
		<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>' . $issues_solved . '</h3>

              <p>Issues Solved</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-hand-peace-o"></i>
            </div>
            <a href="incident.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

		<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>' . $issues_till_date . '</h3>

              <p>Issues Till Date</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-bug"></i>
            </div>
            <a href="incident.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
	</div>
	
			  <div class="widget widget-plain" style="display:none">
				<div class="box-body">
				  <h2 class="dashboard_title" style="display:none"> Weekly orders Stats <span>For the week of Jun 15 - Jun 22, 2011</span> </h2>
				  <div class="dashboard_report first activeState">
					<div class="pad"> <span class="value">' . $account_heads . '</span> Head of Accounts </div>
					<!-- .pad --> 
				  </div>
				  <div class="dashboard_report defaultState">
					<div class="pad"> <span class="value">' . $transactions . '</span> Transactions</div>
					<!-- .pad --> 
				  </div>
				  <div class="dashboard_report defaultState">
					<div class="pad"> <span class="value">' . $vendor . '</span> Vendors</div>
					<!-- .pad --> 
				  </div>
				  
				  
				</div>
				<!-- .box-body --> 
				
			  </div>
			  <!-- .widget -->
			  
			  
			  <div class="widget widget-plain" style="display:none">
				<div class="box-body">
				  <div id="big_stats" class="cf">
					<div class="stat">
					  <h4>Accounts</h4>
					  <span class="value">23</span> </div>
					<!-- .stat -->
					
					<div class="stat">
					  <h4>Clients</h4>
					  <span class="value">2</span> </div>
					<!-- .stat -->
					
					<div class="stat">
					  <h4>Master Clients</h4>
					  <span class="value">13</span> </div>
					<!-- .stat --> 
					
					
					
					<div class="stat">
					  <h4>Service Providers</h4>
					  <span class="value">12</span> </div>
					<!-- .stat -->
					
					<div class="stat">
					  <h4>orders Pending</h4>
					  <span class="value">12</span> </div>
					<!-- .stat -->
					
					<div class="stat">
					  <h4>Tickets Unresolved</h4>
					  <span class="value">12</span> </div>
					<!-- .stat -->
					
				  </div>
				</div>
				<!-- .box-body --> 
				
			  </div>
			  <!-- .widget --> 
			  
			  
			  
			</div>
			<!-- .grid -->
			';
    $data_n = array();
    $data_n["html_head"] = "";
    $data_n["html_title"] = "Dashboard";
    $data_n["html_heading"] = "Dasboard";
    $data_n["html_text"] = $temp;
    return $data_n;
}

// function login_form2()
// {
//     if (is_login()) {
//         echo login_form();
//     } else {
//         echo '<div class="tourmaster-user-top-bar tourmaster-guest">
//                 <span class="tourmaster-user-top-bar-login " data-tmlb="login"><i class="icon_lock_alt"></i><span class="tourmaster-text">Login</span></span>
//                 <span class="tourmaster-user-top-bar-signup " data-tmlb="signup"><i class="fa fa-user"></i><span class="tourmaster-text">Sign Up</span></span>
//                </div>';
//     }
// }

function login_form($type = 0)
{
    $output = '';
    if (is_login()) {
        $output .= '<div class="tourmaster-user-top-bar tourmaster-guest">
                <span class=" "><i class="icon_profile"></i><span class="tourmaster-text">Welcome ' . $_SESSION['users_full_name'] . '</span></span>
                <span class="tourmaster-user-top-bar-signup tourmaster-user-top-bar-logout"><i class="icon_lock_alt"></i><span class="tourmaster-text" onclick="location.href=\'logout.php\'">Logout</span></span>
           </div>
          ';
        return $output;
    }
    list($authUrl, $loginUrl) = get_login_links();
    $login_form = '<form class="tourmaster-login-form tourmaster-form-field tourmaster-with-border" method="post" action="login.php?action=login">
                                <div class="tourmaster-login-form-fields clearfix">
                                    <p class="tourmaster-login-user">
                                        <label>Username</label>
                                        <input type="text" name="email">
                                    </p>
                                    <p class="tourmaster-login-pass">
                                        <label>Password</label>
                                        <input type="password" name="password">
                                    </p>
                                </div>
                                <p class="tourmaster-login-submit">
                                    <input type="submit" name="wp-submit" class="tourmaster-button" value="Sign In!">
                                </p>
                                <p class="tourmaster-login-lost-password"> <a href="my-account/lost-password/index16e7.html?source=tm">Forget Password?</a></p>
                                <input type="hidden" name="rememberme" value="forever">
                                <input type="hidden" name="redirect_to" value="/travel">
                                <input type="hidden" name="source" value="tm">
                                <div>
                                    <div class="nsl-container nsl-container-block nsl-container-embedded-login-layout-below" style="display: block;"><a href="' . $loginUrl . '" rel="nofollow" aria-label="Continue with <b>Facebook</b>" data-plugin="nsl" data-action="connect" data-provider="facebook" data-popupwidth="475" data-popupheight="175"><span class="nsl-button nsl-button-default nsl-button-facebook" style="background-color:#4267b2;"><span class="nsl-button-svg-container"><svg xmlns="http://www.w3.org/2000/svg"><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"></path></svg></span><span class="nsl-button-label-container">Continue with <b>Facebook</b></span></span></a><a href="' . $authUrl . '" rel="nofollow" aria-label="Continue with <b>Google</b>" data-plugin="nsl" data-action="connect" data-provider="google" data-popupwidth="600" data-popupheight="600"><span class="nsl-button nsl-button-default nsl-button-google" data-skin="uniform" style="background-color:#dc4e41;"><span class="nsl-button-svg-container"><svg xmlns="http://www.w3.org/2000/svg"><path fill="#fff" d="M7.636 11.545v2.619h4.331c-.174 1.123-1.309 3.294-4.33 3.294-2.608 0-4.735-2.16-4.735-4.822 0-2.661 2.127-4.821 4.734-4.821 1.484 0 2.477.632 3.044 1.178l2.073-1.997C11.422 5.753 9.698 5 7.636 5A7.63 7.63 0 0 0 0 12.636a7.63 7.63 0 0 0 7.636 7.637c4.408 0 7.331-3.098 7.331-7.462 0-.502-.054-.884-.12-1.266h-7.21zm16.364 0h-2.182V9.364h-2.182v2.181h-2.181v2.182h2.181v2.182h2.182v-2.182H24"></path></svg></span><span class="nsl-button-label-container">Continue with <b>Google</b></span></span></a>
                                    <!--<a href="https://api.twitter.com/oauth/authenticate?oauth_token=egrD0QAAAAAA5oGEAAABZxeRvRk" rel="nofollow" aria-label="Continue with <b>Twitter</b>" data-plugin="nsl" data-action="connect" data-provider="twitter" data-popupwidth="600" data-popupheight="600"><span class="nsl-button nsl-button-default nsl-button-twitter" style="background-color:#4ab3f4;"><span class="nsl-button-svg-container"><svg xmlns="http://www.w3.org/2000/svg"><path fill="#fff" d="M16.327 3.007A5.07 5.07 0 0 1 20.22 4.53a8.207 8.207 0 0 0 2.52-.84l.612-.324a4.78 4.78 0 0 1-1.597 2.268 2.356 2.356 0 0 1-.54.384v.012A9.545 9.545 0 0 0 24 5.287v.012a7.766 7.766 0 0 1-1.67 1.884l-.768.612a13.896 13.896 0 0 1-9.874 13.848c-2.269.635-4.655.73-6.967.276a16.56 16.56 0 0 1-2.895-.936 10.25 10.25 0 0 1-1.394-.708L0 20.023a8.44 8.44 0 0 0 1.573.06c.48-.084.96-.06 1.405-.156a10.127 10.127 0 0 0 2.956-1.056 5.41 5.41 0 0 0 1.333-.852 4.44 4.44 0 0 1-1.465-.264 4.9 4.9 0 0 1-3.12-3.108c.73.134 1.482.1 2.198-.096a3.457 3.457 0 0 1-1.609-.636A4.651 4.651 0 0 1 .953 9.763c.168.072.336.156.504.24.334.127.68.22 1.033.276.216.074.447.095.673.06H3.14c-.248-.288-.653-.468-.901-.78a4.91 4.91 0 0 1-1.105-4.404 5.62 5.62 0 0 1 .528-1.26c.008 0 .017.012.024.012.13.182.28.351.445.504a8.88 8.88 0 0 0 1.465 1.38 14.43 14.43 0 0 0 6.018 2.868 9.065 9.065 0 0 0 2.21.288 4.448 4.448 0 0 1 .025-2.28 4.771 4.771 0 0 1 2.786-3.252 5.9 5.9 0 0 1 1.093-.336l.6-.072z"></path></svg></span><span class="nsl-button-label-container">Continue with <b>Twitter</b></span></span></a>-->
                                    </div>
                                </div></form>';
    if($type > 0) return $login_form;
    $output .= '<div class="tourmaster-user-top-bar tourmaster-guest"><span class="tourmaster-user-top-bar-login " data-tmlb="login"><i class="icon_lock_alt"></i><span class="tourmaster-text">Login</span></span>
                    <div class="tourmaster-lightbox-content-wrap" data-tmlb-id="login">
                        <div class="tourmaster-lightbox-head">
                            <h3 class="tourmaster-lightbox-title">Login</h3><i class="tourmaster-lightbox-close icon_close"></i></div>
                        <div class="tourmaster-lightbox-content">
                            ' . $login_form . '
                            <div class="tourmaster-login-bottom">
                                <h3 class="tourmaster-login-bottom-title">Do not have an account?</h3> <a class="tourmaster-login-bottom-link" href="register">Create an Account</a></div>
                        </div>
                    </div>';
    if (page_name() != "register") {
        $output .= '
               <span class="tourmaster-user-top-bar-signup " data-tmlb="signup"><i class="fa fa-user"></i><span class="tourmaster-text"><a href="register">Sign Up</a> </span></span>';
    }
    $output .= '</div>';
    return $output;
}


function login_form2()
{
    $output = '';
    if (is_login()) {
        return login_form();
    }
    $output .= '<div class="tourmaster-user-top-bar tourmaster-guest">
    <span class="tourmaster-user-top-bar-login " ><i class="icon_lock_alt"></i><span class="tourmaster-text"><a href="'.BASE_URL.'login">Login</a> </span></span>';
    if (page_name() != "register") {
        $output .= '<span class="tourmaster-user-top-bar-signup "><i class="fa fa-user"></i><span class="tourmaster-text"><a href="register">Sign Up</a> </span></span>';
    }
    $output .= '</div>';
    return $output;
}

function get_login_links()
{
    require_once 'library_google/Google_Client.php';
    require_once 'library_google/contrib/Google_Oauth2Service.php';
    $client = new Google_Client();
    $client->setApplicationName("Google UserInfo PHP Starter Application");
    $client->setRedirectUri(page_link('facebook_login', ''));
    $oauth2 = new Google_Oauth2Service($client);
    $authUrl = $client->createAuthUrl('');

//Facebook Login URL

    $fb = new \Facebook\Facebook([
        'app_id' => FB_APP_ID,
        'app_secret' => FB_APP_SECRET,
        'default_graph_version' => 'v2.10',
        //'default_access_token' => '{access-token}', // optional
    ]);

    $helper = $fb->getRedirectLoginHelper();

    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl(page_link('facebook_login', 'facebook_login'), $permissions);
    return array($authUrl, $loginUrl);
}
function logo()
{
  $temp = '
  <div class="branding">
    <div id="site-title" class="assistive-text">Green Island Foundation School</div>
    <div id="site-description" class="assistive-text"></div>
    <a class="same-logo" href="index.php">
      <img class=" preload-me" src="wp-content/uploads/sites/2/2020/12/gifslogo.png" 
      srcset="wp-content/uploads/sites/2/2020/12/gifslogo.png 291w, wp-content/uploads/sites/2/2020/12/gifslogo.png 291w" 
      width="291" height="84"   sizes="291px" alt="Green Island Foundation School" />
    </a>
  </div>';
  return $temp;
}
function islamic_date()
{
  echo '
  <style>
  .date_islamic_header
  {
    direction: rtl;
    float:right;
    line-height:24px;
  }
  </style>
  <script>
    var options = {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric"
    };
    var today = new Date();
    //console.log(today.toLocaleDateString("en-US")); // 9/17/2016
    document.write(today.toLocaleDateString("en-US", options) + \' - &nbsp;<span class="date_islamic_header">(\'+writeIslamicDate(-1, today, 6)+ \'</span><span class="date_islamic_header">\' +\'&nbsp;\'+ writeIslamicDate(-1, today, 5)+ ")</span>"); // Saturday, September 17, 2016
  </script>';
}
function main_menu($mobile = '')
{
  $temp = '<ul id="primary-menu" class="main-nav bg-outline-decoration hover-bg-decoration active-bg-decoration outside-item-remove-margin" role="menubar">';
  if($mobile) $temp = '<ul id="mobile-menu" class="mobile-main-nav" role="menubar">';	
	$links = array(
		'Home' => BASE_URL,
		'About us' => 'about.php',
		'Activities' => 'activities.php',
		'Admission' => 'admission.php',
		'Gallery' => 'gallery.php',
		'LMS' => LMS_URL,
		'Contact' => 'contact.php',
	);
	foreach($links as $title => $href)
	{
    $class_current = '';
    $target = $title == 'LMS' ? ' target="_blank"' : '';
  
    $href2 = ($href == BASE_URL) ? "index" : page_name($href);
    if($href2 == page_name())
      $class_current = 'current_page_item menu-item-home current-menu-item act';
    // alert($class_current);
    $temp .=  '<li class="menu-item menu-item-type-post_type menu-item-object-page  first'.$class_current.'" role="presentation">
      <a href="'.$href.'"'.$target.' data-level="1" role="menuitem">
        <span class="menu-item-text"><span class="menu-text">'.$title.'</span></span>
      </a>
    </li>';
	}
	/* <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-976" role="presentation"><a href='about.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">About Us</span></span></a></li>
   <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-790" role="presentation"><a href='activities/index.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Activities</span></span></a></li>
   <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-979" role="presentation"><a href='admission-form/index.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Admission</span></span></a></li>
   <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-919" role="presentation"><a href='contact/index.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Contact</span></span></a></li>
   <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-641" role="presentation"><a href='#' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Login</span></span></a></li>
   <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1023" role="presentation"><a href='gallery/index.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Gallery</span></span></a></li> -->


     <ul id="mobile-menu" class="mobile-main-nav" role="menubar">
		<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-637 first" role="presentation"><a href='index.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Home</span></span></a></li>
    <li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-966 current_page_item menu-item-976 act" role="presentation"><a href='index.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">About Us</span></span></a></li>
    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-790" role="presentation"><a href='activities/index.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Activities</span></span></a></li>
    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-979" role="presentation"><a href='admission-form/index.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Admission</span></span></a></li> 
    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-919" role="presentation"><a href='contact/index.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Contact</span></span></a></li>
    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-641" role="presentation"><a href='#' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Login</span></span></a></li>
    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1023" role="presentation"><a href='gallery/index.html' data-level='1' role="menuitem"><span class="menu-item-text"><span class="menu-text">Gallery</span></span></a></li>
  </ul>

  /**/
  $temp .= '</ul>';
  return $temp;
}
function get_top_menu($return = false)
{
    $temp = '';
    /*header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');  */
    //header('content-type: application/json; charset=utf-8');
    $menu = array(
        'Home' => BASE_URL,
        'Reservations' => 'reservations',
        /** /'Packages' => 'ziyarat-package',/**/
        /** /'Packages' => array(
            'Ziyarat Package (Personalized)' => 'ziyarat-package',
            'Ziyarat Package (5 Nights)' => 'ziyarat-package-5-nights',
            'Ziyarat Package (7 Nights)' => 'ziyarat-package-7-nights',
            #'Arbaeen Ziyarat Package' => 'arbaeen-ziyarat-package',
            #'Ashura Ziyarat Package' => 'ashura-ziyarat-package',
            #'Executive Package (5 Nights)' => 'ziyarat-package-5-nights',
        ),
        /**/
        //'Blog' => 'blog.php',
        //'Gallery' => '#',
        'Promotions' => 'promotions',
        'Meetings & Events' => 'meetings-events',
        'Spa & Wellness' => 'spa-wellness',
        'Calendar' => 'calendar',
        'Contact Us' => 'contact_us',
    );

    foreach ($menu as $name => $link) {
        if (is_array($link)) {
            $active = '';
            foreach($link as $temp_link)
            {
              if($temp_link == page_url())
              {
                $active = ' current-menu-item';
              }
            }
            $temp .= '<li class="menu-item menu-item-has-children traveltour-normal-menu'.$active.'"><a href="" class="sf-with-ul-pre">' . $name . '</a>';
            $temp .= '<ul class="sub-menu">';
            foreach ($link as $title => $href) {
                if (is_array($href)) {
                    $temp .= '<li class="menu-item menu-item-has-children traveltour-normal-menu">
                                <a href="' . BASE_URL . $__href . '" class="sf-with-ul-pre">' . $title . '</a>';
                    $temp .= '<ul class="sub-menu">';
                    foreach ($href as $_title => $_href) {
                        if (is_array($_href)) {
                            $temp .= '<li class="menu-item menu-item-has-children" data-size="60">
                              <a href="' . BASE_URL . $__href . '" class="sf-with-ul-pre">' . $_title . '</a>';
                            //<a href="'.$href.'">'.$title.'</a>
                            $temp .= '<ul class="dropdown-menu">';
                            foreach ($_href as $__title => $__href) {
                                $temp .= '<li><a href="' . BASE_URL . $__href . '">' . $__title . '</a></li>';
                            }
                            $temp .= '</ul>
							</li>';
                        } else
                            $temp .= '<li><a href="' . BASE_URL . $_href . '">' . $_title . '</a></li>';
                    }
                    $temp .= '</ul>
					</li>';
                } else
                    $temp .= '<li><a href="' . BASE_URL . $href . '">' . $title . '</a></li>';
            }
            $temp .= '</ul></li>';
        } 
        else
        {
          if($link == page_url())
          {
            $active = ' current-menu-item';
          }
          $temp .= '<li class="menu-item menu-item-home traveltour-normal-menu'.$active.'"><a href="' . ($link ? BASE_URL . $link : 'reservations.php') . '" class="sf-with-ul-pre">' . $name . '</a></li>';
        }
    }
    if ($return)
        return $temp;
    echo $temp;
    die;
}

function get_popular_packages()
{
  $sql = "select * from package where is_popular = 1";
  $db = new db2();
  $datas = $db->result($sql);
  if(count($datas) > 1)
  {
    echo '<div class="gdlr-core-pbf-element">
            <div class="tourmaster-tour-item clearfix  tourmaster-tour-item-style-grid tourmaster-item-pdlr tourmaster-tour-item-column-3" id="div_fd3f_28">
                <div class="gdlr-core-flexslider flexslider gdlr-core-js-2 tourmaster-nav-style-rect" data-type="carousel" data-column="3" data-nav="navigation" data-nav-parent="tourmaster-tour-item" data-vcenter-nav="1">
                    <ul class="slides">';
  }
  foreach($datas as $data)
    echo ' <li class="gdlr-core-item-mglr">
            <div class="tourmaster-tour-grid tourmaster-tour-frame tourmaster-price-right-title">
                <div class="tourmaster-tour-thumbnail tourmaster-media-image">
                    <a href="'.$data['link'].'"><img class="lazyload" src="upload/placeholder.jpg" data-src="'.$data["package_image_upload"].'" alt="'.$data["name"].'" 
                    width="700" height="450" /></a>
                </div>
                <div class="tourmaster-tour-content-wrap gdlr-core-skin-e-background">
                    <h3 class="tourmaster-tour-title gdlr-core-skin-title">
                      <a href="'.$data['link'].'" >'.$data["name"].'</a>
                    </h3>
                    <div class="tourmaster-tour-price-wrap "><span class="tourmaster-tour-price"><span class="tourmaster-head">From</span>
                      <span class="tourmaster-tail">$'.$data["selling_price"].'</span></span>
                    </div>
                    <div class="tourmaster-tour-info-wrap clearfix">
                        <div class="tourmaster-tour-info tourmaster-tour-info-duration-text "><i class="fa fa-clock-o"></i>
                          '.$data["number_of_nights"].' Nights
                        </div>
                    </div>
                    <div class="tourmaster-tour-rating tourmaster-tour-rating-empty"></div>
                </div>
            </div>
          </li>';
    if(count($datas) > 1)
    {
      echo '    </ul>
              </div>
          </div>
        </div>';
    }
    return;
}