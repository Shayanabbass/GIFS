<?php
session_start();
require_once("includes/application_top.php"); 
 if($_SESSION["permission_admin_tqcsipk"] != "Yes")
 {
 	redirect("index.php");
 }
$events = new course();
$courses = new courses();
$events = new events();
$events->maxn();
 if($_GET["action"] == "insert")
 {
 	if($events->insert() == 1)
	{
		alert("Event Schedule Inserted Successfully");
		redirect("events.php");
	}
 	
 }
 if($_GET["action"] == "delete")
 {
	if($events->delete() == 1)
	{
		alert("Event Schedule Deleted Successfully");
		redirect("delete_events.php");
	}
 }
 if($_GET["action"] == "update")
 {
	if($events->update() == 1)
	{
		alert("Event Schedule updated Successfully");
		redirect("events.php");
	}
 }
 ?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<title>Lead Auditor | ISO International Organization for Standardization</title>

<meta name="verify-v1" content="V8zUyoe4IlOEIYIqQC2iXUD6ux5DMvw6BEjt8Ygdhnw=" />

<?php 
include("template_iso/meta.php");?>
<style type="text/css">
<!--
-->
</style>
<link href="css.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	background-color: #C1EBFF;
}
-->
</style>
<script src="Scripts/nav.js"></script>
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<!-- InstanceBeginEditable name="head" -->
<title>Lead Auditor | ISO International Organization for Standardization</title>

<link href="css.css" rel="stylesheet" type="text/css" />
<!-- InstanceEndEditable -->
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
<body  onLoad="setScrollBar()"> 
<!-- ImageReady Slices (final.psd) -->
<table width="754" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="AAC2E6" id="Table_01">
<?php 
include('template_iso/iso_main_top.php');
?>
    

	<tr>
	  <td  style="background:url(images/index_06.gif);"></td>
		<td  style="background:url(images/index_06.gif);">
			<img src="images/index_06.gif" width="737" height="1" alt="Spacer" /></td>
	    <td  style="background:url(images/index_06.gif);"></td>
	</tr>
	
	<tr>
	  <td valign="top" bgcolor="AAC2E6" class="body_top_seperator">&nbsp;</td>
      <td valign="middle" bgcolor="AAC2E6" class="body_top_seperator">
      <?php include('template_iso/iso_news_alert.php'); ?>      </td>
      <td valign="top" bgcolor="AAC2E6" class="body_top_seperator">&nbsp;</td>
  </tr>
	<tr>
	  <td valign="top" bgcolor="AAC2E6" class="body_main">&nbsp;</td>
	  <td valign="top" bgcolor="AAC2E6" class="body_main">	    <!-- InstanceBeginEditable name="matter" -->
	    <table width="737" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="527" valign="top"><table width="737" border="0" cellspacing="0" cellpadding="0">
          <tr>
                <td valign="top"><h1>
                Events
                </h1>
<br />
<a href="events.php">All Events</a> &gt;&gt;&gt; Occured Events &gt;&gt; <a href="delete_events.php">Delete Events</a> &gt;&gt; <a href="edit_events.php">Edit Events</a><br />
<p><?php $events->show();?>&nbsp;</p>
</td>
          </tr>
                <tr>
                  <td align="center">&nbsp;</td>
                </tr>
                
                <tr>
                  <td align="center"><img src="images/horzontalseperator_long.jpg" width="100%" height="1" alt="seprator" /></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
          </tr>
        </table>
	  <!-- InstanceEndEditable --></td>
	  <td valign="top" bgcolor="AAC2E6" class="body_main">&nbsp;</td>
  </tr>
	<tr>
	  <td colspan="3" bgcolor="#A8C0E6" >
        <?php 
	  include('bottom.php');
	  ?>
      </td>
  </tr>
	<tr>
	  <td width="8"  style="background:url(images/index_10.gif);" class="copyright"></td>
		<td width="738" height="50"  style="background:url(images/index_10.gif);" class="copyright">Copyright &copy; Protected. <a href="http://www.tqcsipk.com">TQCSI</a> <a href="http://www.tqcsipk.com" class="copyright">Pakistan</a> All Rights Reserved<br />
		  Website Designed and Maintained by <a href="http://www.trafficbuilder.biz" target="_blank">Traffic Builder</a><br />
      <!--Website Designed and Maintained by TQCSI Pakistan
      --></td>
      <td width="8"  style="background:url(images/index_10.gif);" class="copyright"></td>
  </tr>
</table>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-4120689-1");
pageTracker._initData();
pageTracker._trackPageview();
</script> 
</body><!-- InstanceEnd --></html>