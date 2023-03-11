<?php
session_start();
require_once("includes/application_top.php"); 
 if($_SESSION["permission_admin_tqcsipk"] != "Yes")
 {
 	redirect("index.php");
 }
$course = new course();
$courses = new courses();
$events = new events();
if(isset($_GET["action"]))
{
	switch ($_GET["action"])
	{
		case "insert":
			if($events->images_insert() == 1)
			{
				alert("Event Images Inserted Successfully");
				redirect("events_images.php");
			}
			break;
		case "delete":
			if($events->delete_images() == 1)
			{
				alert("Event Images Deleted Successfully");
				redirect("events_images.php");
			}
			break;
		case "update":
			if($events->update_images() == 1)
			{
				alert("Event Images updated Successfully");
				redirect("events_images.php");
			}
			break;
		case "delete_image":
			if($events->delete_image() == 1)
			{
				alert("Event Images deleted Successfully");
				redirect("events_images.php?action=delete_1&event_id=".$_GET["event_id"]);			
			}
			break;
		case "update_sort":
			if($events->update_sort() == 1)
			{
				alert("Event Images Updated Successfully");
				redirect("events_images.php?action=delete_1&event_id=".$_GET["event_id"]);			
			}
			break;
		default:
			break;
	}
}
 ?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
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
<script type="text/javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>

<!-- TinyMCE -->
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->
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
<a href="events.php">Add Events</a> &gt;&gt;&gt; Add Images &gt;&gt; <a href="courses_events_images.php">Associate Courses</a><br />
<p>
<br />
  </p><table width="108" border="0" align="center" cellpadding="0" cellspacing="0" class="button">
  <tr>
    <td width="517"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><div align="center"><a href="events_images.php?action=insert_1">INSERT IMAGES</a></div></td>
      </tr>
    </table></td>
  </tr>
</table>


<p>  <br />
  <?php if(!isset($_GET["action"])){
    //$events->showall();
	$events->images_showall();
	
   ?>
</p>
<?php } if($_GET["action"] == 'add_event'){ ?>
<form action="events_images.php?action=insert&id=<?php echo $_GET["id"]; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="MM_validateForm('course_name','','R','course_date','','R','course_time','','R','course_venue','','R','course_fee','','RisNum','date','','R');return document.MM_returnValue">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="517" class="header_title">tQCSI  INSERT EVENTS</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
       
          <tr>
            <td valign="top" class="small_text">Event Title Ex.</td>
            <td valign="top" class="small_text">   <?php
    $events->show_all();
	
   ?></td>
          </tr>
          <tr>
            <td width="12%" valign="top" class="small_text">Event Title</td>
            <td width="88%"><textarea name="event_title" class="inputs" id="event_title" style="width:580px;"></textarea></td>
          </tr>
    
          <tr>
            <td colspan="2"><label>
              <input type="submit" name="button" id="button" value="Submit" />
              <a href="events_images.php?action=add_event">
              <input type="reset" name="button2" id="button2" value="Reset" /></a> 
              <a href="events_images.php">Cancel</a></label></td>
          </tr>
          
      </table></td>
    </tr>
  </table>
  </form>
<?php }
 if($_GET["action"] == 'insert_1'){ ?>
<form action="events_images.php?action=insert" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="MM_validateForm('course_name','','R','course_date','','R','course_time','','R','course_venue','','R','course_fee','','RisNum','date','','R');return document.MM_returnValue">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%" class="header_title">tQCSI  INSERT IMAGES</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="24%" valign="top" class="small_text">Event Title</td>
            <td width="76%">&nbsp;</td>
          </tr>
          
          <tr>
            <td colspan="2"><?php 
	$events->images_events();
			?></td>
            </tr>
                <tr>
            <td colspan="2" valign="top" class="small_text">Event Images</td>
            </tr>
          
          <tr>
            <td colspan="2" valign="top" class="small_text"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                <tr>
                  <td>Sort</td> 
                  <td>Large Image
                  <input type="hidden" name="MAX_FILE_SIZE" value="9999999999" />
                  </td>
                  <td>Small Image</td>
                </tr>
                <?php
                for($i=1; $i<101; $i++)
				{
					$name = 'esort'.$i;
					$elarge = 'elarge'.$i;
					$esmall = 'esmall'.$i;
					echo '
					<tr>
					 <td><input name="'.$name.'" type="text" id="'.$name.'" value="'.$i.'" /></td>
					  <td><input type="file" name="'.$elarge.'" id="'.$elarge.'" class="inputs" /></td>
					  <td><input type="file" name="'.$esmall.'" id="'.$esmall.'" class="inputs" /></td>
					</tr>';
				}
                ?>
              </table></td>
            </tr>
          <tr>
            <td colspan="2"><label>
              <input type="submit" name="button" id="button" value="Submit" />
              <a href="events_images.php?action=insert_1">
              <input type="reset" name="button2" id="button2" value="Reset" /></a> 
              <a href="events_images.php">Cancel</a></label></td>
          </tr>
          
      </table></td>
    </tr>
  </table>
  </form>
<?php } 
	if($_GET["action"] == 'delete_1')
	{
		  $events->edit_images();
		  echo '<br /><table width="108" border="0" align="center" cellpadding="0" cellspacing="0" class="button">
  <tr>
    <td width="517"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><div align="center"><a href="events_images.php">BACK TO ADD IMAGES</a></div></td>
      </tr>
    </table></td>
  </tr>
</table>';
	 }  
	 
	 
?>
</p></td>
          </tr>
                <tr>
                  <td align="center">
                 <form name="form1" action="<?php echo $_SERVER['PHP_SELF'].'?action=update_sort&event_id='.$_GET["event_id"].'&src='.$_GET["src"]; ?>" method="post"> <?php if ($_GET["action"] == "edit_image")
				  {
				   	$events->edit_sort();
				  }
				  ?></form>
                  </td>
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
<?php include('template_iso/footer.php'); ?>
</body>
<!-- InstanceEnd --></html>