<?php
function activity()
{
	// debgallery-demoug_r($_REQUEST);
	$b1 = new latest_stories();
	$id = $_GET["post_id"];
	$category_id = $_GET["category_id"];
	$action = $_GET["action"];
  $title = 'ACTIVITIES';
  if($id)
  {
	// $title = $a["post_name"];
	return $b1->detail();
  }
  if(!$action)
  {
    $temp = '
		</p>
		<p class="fs-6 mb-5 mt-5 text-center">
			We ensure that our students are able to get as much exposure as possible. This is why we aim to broaden their horizon by exposing them to diverse activities for them to become more creative and versatile thinkers. These activities include, festivities celebrations, naat competition, art and linguistic competition, science competition, and much more. These activities challenge an individual enough to unleash their inner potential to excel in their academic as well as personal life and all other spectrums ahead.
		</p>
		';
		$sql = "SELECT post_id, `post_name`, event_on, `post_image_upload`, `post_description`, `post_category`, gallery_link, UNIX_TIMESTAMP(`event_on`) as event_on, event_timing  
		FROM `post` ORDER BY post_added_on DESC
		LIMIT 0, 20";
		$posts = $b1->result($sql);
		foreach($posts as $a)
		{
			$posted_at = json_decode($a['post_category']);
			$post_description = ($a['post_description']);
			$posted_at = explode('___', $posted_at[0]);
			$category_name = $posted_at[1];
			$posted_at = $a["posted_at"];
			// debug_r($posted_at);
			// $post_description =
			// if($post_description && strpos($post_description, ".", 200) > 0)
			// {
			// 	// $post_description = substr($post_description, 0, strpos($post_description, ".", 200));
			// }
			$temp .= '
				<div class="flex column '.$rowReverse.' mb-5 pb-5" >
				<div class="">
					<div class="activityImage">
					<div class="zoomInHover">
						<img src="'.$a['post_image_upload'].'" alt="Activity Image">
					</div>
					</div>

				</div>

				<div class="alignCenter">
					<h4 class="activityHeading">'.$a['post_name'].'</h4>
					<div class="flex" style="justify-content:flex-start; margin: 5px 0;">
					<span class="my-1 d-block font-weight-bold" style="margin-right: 15px; font-size: 12px;">
						<i class="far fa-calendar-alt"></i>
						'.date('j F Y ', $a["event_on"]).'</span>
					<span class="my-1 d-block font-weight-bold" style="margin-right: 15px; font-size: 12px;">
						<i class="far fa-clock"></i>
						'.$a['event_timing'].'</span>

								'.$category_name.'
					</div>

					<p class="activityText">
						'.htmlspecialchars_decode($post_description).'
					</p>
					'.($a['gallery_link'] ? '<a href="'.$a['gallery_link'].'" target="_blank" class="btn btn-success hover">View Gallery</a>' : '').'
				</div>
				</div>';
				if($rowReverse == '') $rowReverse = 'rowReverse';
				else $rowReverse = '';
		}
    	$data_n["html_title"] = $title.'';
		$data_n["html_heading"] = $title;
		$data_n["html_text"] = $temp;
		return $data_n;
	}

	// debug_r("a");
  return '';
	// return blog();
}

/* functions without return */
function makeClickableLinks($text) {
	$pattern = '/(?<=[>]).+(?=[<][^<][^\/][>]["])/i';
	$pattern = "/(http:\/\/)?([a-zA-Z0-9\-.]+\.[a-zA-Z0-9\-]+([\/]([a-zA-Z0-9_\/\-.?&%=+])*)*)/";
	$text = preg_replace($pattern, '<a href="http://$2">$2</a>', $text);
	return $text;

	$text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\1">\\1</a>', $text);
	$text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="http://\\2">\\2</a>', $text);
	$text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})', '<a href="mailto:\\1">\\1</a>', $text);
	return $text;
}
function to_blog_url($name)
{
	$id = get_tuple("post", $name, "post_name");
	$id = ($id["post_id"]);
    // debug_r($name);
	return 'activity.php?post_id='.$id;
	// debug_r(BLOG_URL.str_replace(array(" ", "-", "\n"), array("-", "_", ''), $name).'.html');
	return BLOG_URL.str_replace(array(" ", "-", "\n"), array("-", "_", ''), $name).'';
}
function to_blog_url_id($id)
{
	return 'activity.php?post_id='.$id;
}
function get_blog_names($name)
{
	$name = str_replace("-", " ", $name);
	$name = str_replace("_", "-", $name);
	$id = get_tuple("post", $name, "post_name");
	$id = ($id["post_id"]);
	return $id;
}
