<?php

// list($authUrl, $loginUrl) = get_login_links();


//Facebook Login URL END


if(!isset($_SESSION["users_id"]))
	$mess = "Login";
else
	$mess = "Main Menu";
	  if ($_SESSION["action"] == "login")
					{
						$_SESSION["action"] = '';
						$u->users_check2();
					}
					if($_SESSION["permission_dynamic_cmm"] == 'Yes')
					{
						?>

					<UL class="menu" id="services-list">
                        <!-- permissions start -->
                        <div id="welcome-note">
                        Welcome <br /><?php echo $_SESSION["users_full_name"]; ?>
                        </div>
                        <?php $u->permissions();?>
                        <li><a href="logout.php">Logout</a></li>
                        <!-- permissions start -->
                    </UL>
	        		<?php
					}
					else
					{
						echo($_SESSION["error_message"]);
					?>



	<form id="form1" method="post" action="trafficbuilder/action.php?action=login&url=<?php echo page_url(); ?>" onsubmit="MM_validateForm('users_name','','R','users_password','','R');return document.MM_returnValue">
      <div class="form-group has-feedback">
        <input name="users_name" type="text" class="form-control" id="users_name" placeholder="email@example.com" tabindex="1" autocomplete="off" value="">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="users_password" type="password" class="form-control" id="users_password" tabindex="2" autocomplete="off"  onfocus="this.value=''" value="">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    </div> <!---->
    <!-- /.social-auth-links -->
 	<!--  end login-inner -->


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="templates/template2/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="templates/template2/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="templates/template2/plugins/iCheck/icheck.min.js"></script>
<script>
  jQuery(function () {
    jQuery('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
		        <?php
					}
    echo $_SESSION["users_id"];die;
					?>
