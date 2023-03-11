<?php 
class blog extends db2
{
	function show($category_id = '')
	{
		$key = $_GET["keyword"];
		if($category_id)
		{
			$sql1 = "SELECT p.*, UNIX_TIMESTAMP(post_added_on) as po 
				FROM  post p inner join post_to_category ptc
					on p.post_id = ptc.post_id
				WHERE ptc.category_id = $category_id";
		}
		else
		{
			$sql1 = "SELECT * , UNIX_TIMESTAMP(post_added_on) as po
				FROM  post 
				WHERE (
				post_name LIKE '%".$key."%'
				OR  post_name LIKE '%".$key."%'
				OR  post_description LIKE '%".$key."%'
				)";
		}
		$result1 = $this->result($sql1);
		$total = count($result1);
		$max = 10;
	 	$first = 1;
		if(isset($_REQUEST["first"]) && $_REQUEST["first"] != "")
			$first = $_REQUEST["first"];
//		$first--;
		$last = $max;
//		alert();	%$total
		$filter = ' limit '.($first-1).', '.$max;
		// $sql = "select *, UNIX_TIMESTAMP(post_added_on) as po from post
		// 		ORDER BY post_name ASC
		// 		".$filter;
		
		$sql = $sql1.$filter;

		$result = $this->result($sql);
		$temp .= '
		
		<div class="gap"></div>
		<div class="row">
			<div class="col-md-9">';
		if($total == 0)
		{
			$temp .= 'No Records found';
		}
		foreach($result as $a)
		{
			$sql2 = "select count(*) as TOT from comment where post_id = ".$a["post_id"];
			$b = $this->result($sql2, 1);
			$count = $b["TOT"];
			$pic_product = $a["post_image_upload"];
			if($pic_product == "")
			{
				$pic_product = 'img/postnoimage.jpg';
			}

			$temp .= '
        <!-- START BLOG POST -->
				<div class="article post">';
			switch($a['post_type_name'])
			{
				case "Default":
					break;
				case "Link":
					$temp .= '<header class="post-header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><a class="post-link" href="'.to_blog_url($a["post_name"]).'">'
						.strip_tags($a['post_description'], '').
					'</a></header>';
					break;
				case "Video":
					$temp .= '<header class="post-header">
							<iframe src="'.strip_tags($a['post_description'], '').'" frameborder="0" allowfullscreen></iframe>
					</header>';
					break;
				case "Image":
					$temp .= '<header class="post-header"><a class="hover-img" href="'.to_blog_url($a["post_name"]).'">'
												.'<img src="'.$a['post_image_upload'].'" alt="'.$a['post_name'].'" title="196_365" /><i class="fa fa-link box-icon-# hover-icon round"></i>'.
										'</a>
								</header>';
			}
			$user = get_user($a['post_added_by_id']);

			
			// <a href="'.to_blog_url($a["post_name"]).'">Web</a>, 
			// <a href="'.to_blog_url($a["post_name"]).'">Travel</a>, 
			// <a href="'.to_blog_url($a["post_name"]).'">Digital</a>

			$categories = json_decode('['.$a['post_category'].']');
			$category_list = array();
			foreach($categories[0] as $category)
			{
				$temp_arr = explode("___", $category);
				$category_list[] = '<a href="?category_id='.$temp_arr[0].'">'.$temp_arr[1].'</a>';
			}
			$temp .= '
					<div class="post-inner">
							<h4 class="post-title"><a class="text-darken" href="'.to_blog_url($a["post_name"]).'">'.$a['post_name'].'</a></h4>
							<ul class="post-meta">
									<li>
										<i class="fa fa-calendar"></i><a href="'.to_blog_url($a["post_name"]).'">'.date("d F, Y", $a["po"]).'</a>
									</li>
									<li class="hidden">
										<i class="fa fa-user"></i><a href="'.to_blog_url($a["post_name"]).'">'.$user['users_full_name'].'</a>
									</li>
									<li>
										<i class="fa fa-tags"></i>'.implode(', ', $category_list).'
									</li>
									<li>
										<i class="fa fa-comments"></i><a href="'.to_blog_url($a["post_name"]).'">'.$count.' Comments</a>
									</li>
							</ul>
							<p class="post-desciption"> '.substr(strip_tags(htmlspecialchars_decode1($a["gallery_link"])), 0, 400).'......</p><a class="btn btn-small btn-primary" href="'.to_blog_url($a['post_name']).'">Read More</a>
					</div>
			</div>
			<!-- END BLOG POST -->';
		}		
		$end = ($first-1+$max);
		if($category_id)
			$link = '?category_id='.$category_id.'&first=';
		else
			$link = '?keyword='.$key.'&type[]=blog&first=';
		if($total == 0)
		{
			$first = 0;
			//return $temp;
		}
		else 
		{
			if($end>$total)
			{
				$end = $total;
			}
			$range = $first.'-'.$end;
			//$temp .= "\$total = $total<br />\$max = $max<br />\$first = $first<br />\$end = $end";
			$temp .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="small_font">
					<tr>
									<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="td" style="padding-top:12px; font:12px Calibri">Displaying '.$range.' of '.$total.'</td>
								<td><table border="0" align="right" cellpadding="0" cellspacing="0">
									<tr class="header">
										<td width="85" valign="middle" class="header">';
						if($first>1)
						{
						$temp .= '
										<div id="previous">
											<a href="'.$link.($first-$max).'">&laquo; Previous
											</a></div>';
						}
						$width = 280/7*$total;
						$temp .= '
										</td>
										<td width="'.$width.'" align="left" valign="middle"><ul class="pagination">';
						for($i=1, $j=1;$i<=$total && $i<8;$j++)
						{
							if($i!=$first)
								$temp .= '<li><a href="'.$link.$i.'">'.$j.'</a></li>';
							else
								$temp .= '<li class="active"><a>'.$j.'</a></li>';
							$i+=$max;
						}
						
						if($j>($maxpages-1))
						{
							//$temp .= '<li><a href="'.$link.($max*$maxpages-$max+1).'">.....</a></li>';
						}
						$temp .= '
										</ul></td>
										<td width="33" valign="middle" class="header">&nbsp;</td>
										<td width="58" valign="middle" class="header">';
					if($total>($max+$first-1))
						{
						$temp .= '
										<div id="next">
										<a href="'.$link.($first+$max).'">Next &raquo;</a>
										</div>';
						}
						$temp .= '
										</td>
									</tr>
								</table></td>
							</tr>
						</table></td>

								</tr>
					</table>';
					}
				
				
    $temp .= '<div class="gap"></div>
    </div>
    <div class="col-md-3">
        '.$this->sidebar().'
    </div>
</div>
			  ';
		return $temp;
	}
	function sidebar()
	{
		$categories = $this->result("select c.category_id, c.category_name, count(ptc.post_id) as tot from category c left join post_to_category ptc on ptc.category_id = c.category_id group by category_id");
		$temp .= '<aside class="sidebar-right">
		<div class="sidebar-widget">
				<div class="Form">
						<form method="GET" action="">

							<div class="input-group">
								<input type="text" class="form-control" name="keyword" value="'.$_GET["keyword"].'" placeholder="Search for...">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button">Go!</button>
								</span>
							</div><!-- /input-group -->
						</form>
				</div><!--form-->
		</div><!--sidebar_widget-->
		<div class="sidebar-widget">
				<h4>Categories</h4>
				<ul class="icon-list list-category">';
				foreach ($categories as $category) {
						$post_id = $category['post_id'];
						$temp .= '
						<li><a href="?category_id=' . $category["category_id"].'"><i class="fa fa-angle-right"></i>' . $category["category_name"].' <small >(' . $category["tot"] . ')</small></a></li>';
				}
				$temp .= '
				</ul>
		</div>
		<div class="sidebar-widget">
			<a href="#"><img src="./Advertisment_Banner/BlogPageAsideBanner.jpg"></a>
		</div>
		<div class="sidebar-widget hidden">
				<h4>Popular Posts</h4>
				<ul class="thumb-list">
						<li>
								<a href="#">
										<img src="img/70x70.png" alt="Image Alternative text" title="Viva Las Vegas" />
								</a>
								<div class="thumb-list-item-caption">
										<p class="thumb-list-item-meta">Jul 18, 2014</p>
										<h5 class="thumb-list-item-title"><a href="#">Rhoncus fusce</a></h5>
										<p class="thumb-list-item-desciption">Semper suspendisse varius ligula habitasse</p>
								</div>
						</li>
						<li>
								<a href="#">
										<img src="img/70x70.png" alt="Image Alternative text" title="4 Strokes of Fun" />
								</a>
								<div class="thumb-list-item-caption">
										<p class="thumb-list-item-meta">Jul 18, 2014</p>
										<h5 class="thumb-list-item-title"><a href="#">Montes non</a></h5>
										<p class="thumb-list-item-desciption">Accumsan hendrerit velit congue lobortis</p>
								</div>
						</li>
						<li>
								<a href="#">
										<img src="img/70x70.png" alt="Image Alternative text" title="Cup on red" />
								</a>
								<div class="thumb-list-item-caption">
										<p class="thumb-list-item-meta">Jul 18, 2014</p>
										<h5 class="thumb-list-item-title"><a href="#">Varius feugiat</a></h5>
										<p class="thumb-list-item-desciption">Euismod scelerisque phasellus hendrerit magna</p>
								</div>
						</li>
				</ul>
		</div>
		<div class="sidebar-widget hidden">
				<h4>Twitter Feed</h4>
				<div class="twitter" id="twitter"></div>
		</div>
		<div class="sidebar-widget hidden">
				<h4>Recent Comments</h4>
				<ul class="thumb-list thumb-list-right">
						<li>
								<a href="#">
										<img class="rounded" src="img/70x70.png" alt="Image Alternative text" title="Afro" />
								</a>
								<div class="thumb-list-item-caption">
										<p class="thumb-list-item-meta">7 minutes ago</p>
										<h4 class="thumb-list-item-title"><a href="#">Alison Mackenzie</a></h4>
										<p class="thumb-list-item-desciption">Himenaeos cras platea aliquet consequat justo congue...</p>
								</div>
						</li>
						<li>
								<a href="#">
										<img class="rounded" src="img/70x70.png" alt="Image Alternative text" title="AMaze" />
								</a>
								<div class="thumb-list-item-caption">
										<p class="thumb-list-item-meta">6 minutes ago</p>
										<h4 class="thumb-list-item-title"><a href="#">Blake Hardacre</a></h4>
										<p class="thumb-list-item-desciption">Sit gravida augue neque consequat elementum ultrices...</p>
								</div>
						</li>
						<li>
								<a href="#">
										<img class="rounded" src="img/70x70.png" alt="Image Alternative text" title="Gamer Chick" />
								</a>
								<div class="thumb-list-item-caption">
										<p class="thumb-list-item-meta">9 minutes ago</p>
										<h4 class="thumb-list-item-title"><a href="#">Bernadette Cornish</a></h4>
										<p class="thumb-list-item-desciption">Pellentesque lacus sociosqu ac malesuada urna condimentum...</p>
								</div>
						</li>
				</ul>
		</div>
		<div class="sidebar-widget hidden">
				<h4>Archive</h4>
				<ul class="icon-list list-category">
						<li><a href="#"><i class="fa fa-angle-right"></i>July 2014</a>
						</li>
						<li><a href="#"><i class="fa fa-angle-right"></i>June 2014</a>
						</li>
						<li><a href="#"><i class="fa fa-angle-right"></i>May 2014</a>
						</li>
						<li><a href="#"><i class="fa fa-angle-right"></i>April 2014</a>
						</li>
						<li><a href="#"><i class="fa fa-angle-right"></i>March 2014</a>
						</li>
						<li><a href="#"><i class="fa fa-angle-right"></i>February 2014</a>
						</li>
						<li><a href="#"><i class="fa fa-angle-right"></i>January 2014</a>
						</li>
						<li><a href="#"><i class="fa fa-angle-right"></i>December 2014</a>
						</li>
				</ul>
		</div>
		<div class="sidebar-widget hidden">
				<h4>Gallery</h4>
				<div class="row row-no-gutter">
						<div class="col-md-4">
								<a class="hover-img" href="#">
										<img src="img/100x100.png" alt="Image Alternative text" title="The Big Showoff-Take 2" />
								</a>
						</div>
						<div class="col-md-4">
								<a class="hover-img" href="#">
										<img src="img/100x100.png" alt="Image Alternative text" title="Good job" />
								</a>
						</div>
						<div class="col-md-4">
								<a class="hover-img" href="#">
										<img src="img/100x100.png" alt="Image Alternative text" title="4 Strokes of Fun" />
								</a>
						</div>
						<div class="col-md-4">
								<a class="hover-img" href="#">
										<img src="img/100x100.png" alt="Image Alternative text" title="b and w camera" />
								</a>
						</div>
						<div class="col-md-4">
								<a class="hover-img" href="#">
										<img src="img/100x100.png" alt="Image Alternative text" title="Happy Bokeh Day" />
								</a>
						</div>
						<div class="col-md-4">
								<a class="hover-img" href="#">
										<img src="img/100x100.png" alt="Image Alternative text" title="sunny wood" />
								</a>
						</div>
						<div class="col-md-4">
								<a class="hover-img" href="#">
										<img src="img/100x100.png" alt="Image Alternative text" title="a dreamy jump" />
								</a>
						</div>
						<div class="col-md-4">
								<a class="hover-img" href="#">
										<img src="img/100x100.png" alt="Image Alternative text" title="Spidy" />
								</a>
						</div>
						<div class="col-md-4">
								<a class="hover-img" href="#">
										<img src="img/100x100.png" alt="Image Alternative text" title="Me with the Uke" />
								</a>
						</div>
				</div>
		</div>
		<div class="sidebar-widget hidden">
				<h4>Facebook</h4>
				<div class="fb-like-box" data-href="https://www.facebook.com/FacebookDevelopers" data-colorscheme="light" data-show-faces="1" data-header="1" data-show-border="1" data-width="233"></div>
		</div>
</aside>';
		return $temp;
	}
	function show2($id = '')
	{
		if($id == '')
		{
			$id = $_GET["post_id"];
		}
		$sql = "select *, UNIX_TIMESTAMP(post_added_on) as po from post where post_id = ".$id;
		$a = $this->result($sql, 1);
		$categories = json_decode('['.$a['post_category'].']');
		$category_list = array();
		foreach($categories[0] as $category)
		{
			$temp_arr = explode("___", $category);
			$category_list[] = '<a href="?category_id='.$temp_arr[0].'">'.$temp_arr[1].'</a>';
		}
		$sql2 = "select count(*) as TOT from comment where post_id = ".$a["post_id"];
		$b = $this->result($sql2, 1);
		$count = $b["TOT"];
		$pic_product = $a["post_image_upload"];
		if($pic_product == "")
		{
			$pic_product = 'img/postnoimage.jpg';
		}
		
		$temp .= '
<div class="gap"></div><div class="container">
		<div class="row">
				<div class="col-md-9">
						<article class="post">';
								switch($a['post_type_name'])
								{
									case "Default":
										break;
									case "Link":
										$temp .= '<header class="post-header"><a class="post-link" href="'.to_blog_url($a["post_name"]).'">'
											.strip_tags($a['post_description'], '').
										'</a></header>';
										break;
									case "Video":
										$temp .= '<header class="post-header">
												<iframe src="'.strip_tags($a['post_description'], '').'" frameborder="0" allowfullscreen></iframe>
										</header>';
										break;
									case "Image":
										$temp .= '<header class="post-header"><a class="hover-img" href="'.to_blog_url($a["post_name"]).'">'
																	.'<img src="'.$a['post_image_upload'].'" alt="'.$a['post_name'].'" title="196_365" /><i class="fa fa-link box-icon-# hover-icon round"></i>'.
															'</a>
													</header>';
								}
								$user = get_user($a['post_added_by_id']);
			$temp .= '
								<div class="post-inner">
										<h4 class="post-title text-darken">'.$a['post_name'].'</h4>
										<ul class="post-meta">
												<li><i class="fa fa-calendar"></i><a href="javascript:void()">'.date("d M, Y", $a["po"]).'</a>
												</li>
												<li class="hidden"><i class="fa fa-user"></i><a href="javascript:void()">'.$user["users_full_name"].'</a>
												</li>
												<li><i class="fa fa-tags"></i>'.implode(', ', $category_list).'
												</li>
												<li><i class="fa fa-comments"></i><a href="javascript:void()">'.$count.' Comments</a>
												</li>
										</ul>
										<p>'.htmlspecialchars_decode1($a["gallery_link"]).'</p>
										<p>
										    <img src="./Advertisment_Banner/BlogPageBanner.jpg"/>
										</p>
								</div>
						</article>
					
								';
		//--------------------------------------------------------Show Comments
		$sql2 = "select *, UNIX_TIMESTAMP(commment_added_on) as con  from comment where post_id = ".$a["post_id"];
		$result2 = $this->result($sql2);
		if(count($result2))
		{
			$temp .= '	<h2>Post Discussion</h2>
						<!-- START COMMENTS -->
						<ul class="comments-list">';
		}
		foreach($result2 as $b)
		{
			$comment = $b["comment"];
			if($comment_added_by_id)
			{
				$by = get_tuple('users', $b['comment_added_by_id'], 'users_id');
				$by = $by["users_first_name"].' '.$by["users_last_name"];
				$pic = $by["users_image_upload"];
			}
			else
			{
				$by = $b['full_name'];
			}
			
			if($pic == "")
			{
				$pic = 'img/noimage.jpg';
				$pic = 'guest_images/avatar.png';
			}
			if($_SESSION["groups_id"] == 3 || $_SESSION["groups_id"] == 11)
			{
				//Show delete button
				//commments once added can only be deleted by admin
				$delete = '<input id="button2" type="button" value="DELETE" onclick="location.href=\'blog.php?action=delete&id='.$b["comment_id"].'&post_id='.$id.'\'" />';
			}
			$on = date("M d, Y", $b["con"]);
			
			$temp .= '<li>
						<div class="article comment" inline_comment="comment">
								<div class="comment-author">
										<img src="'.$pic.'" alt="" title="" style="height: 50px; width: 50px;" />
								</div>
								<div class="comment-inner"><span class="comment-author-name hidden">'.$by.'</span>
										<p class="comment-content">'.makeClickableLinks(strip_tags(htmlspecialchars_decode1($comment), '')).'</p>
										<span class="comment-time">'.$on.'</span>'.$delete.'
								</div>
						</div>
				</li>';
				/**<a class="comment-reply" href="#"><i class="fa fa-reply"></i> Reply</a>
										<a class="comment-like" href="#"><i class="fa fa-heart"></i> 7</a> */ 
			}
			if(count($result2))
			{
				$temp .= '</ul>';
			}

		$temp_deleted = '	<h2>Post Discussion</h2>
		<!-- START COMMENTS -->
		<ul class="comments-list">
				<li>
						<div class="article comment" inline_comment="comment">
								<div class="comment-author">
										<img src="img/50x50.png" alt="Image Alternative text" title="Gamer Chick" />
								</div>
								<div class="comment-inner"><span class="comment-author-name">Brandon Burgess</span>
										<p class="comment-content">Iaculis dictumst dui taciti himenaeos taciti arcu non sollicitudin viverra id blandit cursus ac</p><span class="comment-time">15 seconds ago</span><a class="comment-reply" href="#"><i class="fa fa-reply"></i> Reply</a><a class="comment-like" href="#"><i class="fa fa-heart"></i> 35</a>
								</div>
						</div>
				</li>
				<li>
						<div class="article comment" inline_comment="comment">
								<div class="comment-author">
										<img src="img/50x50.png" alt="Image Alternative text" title="Spidy" />
								</div>
								<div class="comment-inner"><span class="comment-author-name">Bernadette Cornish</span>
										<p class="comment-content">Iaculis suspendisse libero posuere fermentum enim dictumst malesuada fames placerat</p><span class="comment-time">15 seconds ago</span><a class="comment-reply" href="#"><i class="fa fa-reply"></i> Reply</a><a class="comment-like" href="#"><i class="fa fa-heart"></i> 27</a>
								</div>
						</div>
										<ul>
												<li>
														<div class="article comment" inline_comment="comment">
																<div class="comment-author">
																		<img src="img/50x50.png" alt="Image Alternative text" title="Good job" />
																</div>
																<div class="comment-inner"><span class="comment-author-name">Joseph Watson</span>
																		<p class="comment-content">Dolor adipiscing quis amet id cubilia euismod primis amet porta</p><span class="comment-time">15 seconds ago</span><a class="comment-reply" href="#"><i class="fa fa-reply"></i> Reply</a><a class="comment-like" href="#"><i class="fa fa-heart"></i> 21</a>
																</div>
														</div>
														<ul>
																<li>
																		<div class="article comment" inline_comment="comment">
																				<div class="comment-author">
																						<img src="img/50x50.png" alt="Image Alternative text" title="Ana 29" />
																				</div>
																				<div class="comment-inner"><span class="comment-author-name">Olivia Slater</span>
																						<p class="comment-content">Molestie vehicula eu arcu praesent commodo sociis nunc duis vel sem senectus nunc</p><span class="comment-time">15 seconds ago</span><a class="comment-reply" href="#"><i class="fa fa-reply"></i> Reply</a><a class="comment-like" href="#"><i class="fa fa-heart"></i> 28</a>
																				</div>
																		</div>
																</li>
														</ul>
														<li>
																<div class="article comment" inline_comment="comment">
																		<div class="comment-author">
																				<img src="img/50x50.png" alt="Image Alternative text" title="Luca" />
																		</div>
																		<div class="comment-inner"><span class="comment-author-name">Leah Kerr</span>
																				<p class="comment-content">Eros est aliquam ad ridiculus nam ultricies suscipit penatibus duis nullam amet per netus non phasellus vulputate massa sodales habitant himenaeos</p><span class="comment-time">15 seconds ago</span><a class="comment-reply" href="#"><i class="fa fa-reply"></i> Reply</a><a class="comment-like" href="#"><i class="fa fa-heart"></i> 5</a>
																		</div>
																</div>
														</li>
										</ul>
										<li>
												<div class="article comment" inline_comment="comment">
														<div class="comment-author">
																<img src="img/50x50.png" alt="Image Alternative text" title="Chiara" />
														</div>
														<div class="comment-inner"><span class="comment-author-name">John Doe</span>
																<p class="comment-content">Mi massa himenaeos ipsum eleifend potenti tempor pretium nunc magnis dignissim molestie fermentum natoque ornare faucibus imperdiet cursus class ad lacus viverra tempor cras mollis eros sociosqu</p><span class="comment-time">15 seconds ago</span><a class="comment-reply" href="#"><i class="fa fa-reply"></i> Reply</a><a class="comment-like" href="#"><i class="fa fa-heart"></i> 20</a>
														</div>
												</div>
										</li>
						</ul>
						<!-- END COMMENTS -->';
		$temp .= '';
		//--------------------------------------------------------Add Comments 
		if($_SESSION["users_id"] && 0)
		{
			$name = '?action=new_comment&post_id='.$id;
	//		$temp .= $name;
			$temp .= '
			 <form id="form5" name="form5" method="post" action="'.$name.'" enctype="multipart/form-data" onsubmit="return check()">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				  <tr>
					<td height="30" colspan="2">&nbsp;</td>
					</tr>
				  <tr>
					<td colspan="2" bgcolor="#f5f5f5" style="padding:15px;"><span class="float-left"><h3>Add Comment</h3>
					</span><span class="float-right"> (URLs automatically linked.)
					</span><br />
					<label>
					<textarea name="comment" id="comment" cols="45" rows="5" style="width:100%; height:120px; margin:10px 0px;"></textarea>
					</label>
					<span class="float-right">
					<label>
					<input name="button3" type="submit" class="float-right" id="button3" value="Submit" />
					</label>
					</span><span class="float-left">Email address is not displayed with comment.</span></td>
				  </tr>         
				</table>
				</form>
				';
		}
		else
		{
			$name = '?action=new_comment_guest&post_id='.$id;
			$temp .= '
			<h3>Leave a Comment</h3>
			<form id="form5" name="form5" method="post" action="'.$name.'" enctype="multipart/form-data" onsubmit="return check2()">
			<div class="row">
							<div class="col-md-4">
									<div class="form-group">
											<label>Name</label>
											<input class="form-control" type="text" id="full_name" name="full_name" />
									</div>
							</div>
							<div class="col-md-4">
									<div class="form-group">
											<label>E-mail</label>
											<input class="form-control" type="text" id="email" name="email" />
									</div>
							</div>
							<div class="col-md-4">
									<div class="form-group">
											<label>Website</label>
											<input class="form-control" type="text" id="website" name="website" />
									</div>
							</div>
					</div>
					<div class="form-group">
							<label>Comment (URLs are automatically linked) </label>
							<textarea class="form-control" id="comment" name="comment"></textarea>
					</div>
					<input class="btn btn-primary" type="submit" value="Leave a Comment" />
			</form>';
		}
		$temp .= '
		<div class="gap"></div>
					</div>
					<div class="col-md-3">
							'.$this->sidebar().'
					</div>
			</div>
			</div>';
		return $temp;
	}
	function insert()
	{
		checkLogin();
		$comment = $_REQUEST["comment"];
		if( (substr($comment, 0, 3) == "<p>" ))
		{
			$temp = substr(substr($comment, 3, strlen($comment)), 0, strlen($comment)-3);
			$comment = $temp;
			$comment = substr($comment, 0, strlen($comment)-4);
			$comment = htmlspecialchars($comment);
		}
		
		$post_id = $_GET["post_id"];
		
		$comment_added_by_id  = $_SESSION["users_id"];
		$sql = 'Insert into comment (comment, post_id, comment_added_by_id) VALUES ( \''.$comment.'\', \''.$post_id.'\', \''.$comment_added_by_id.'\')';
		//debug_r($sql);
		$result = $this->sqlq($sql);
		if($result || 1)
		{
			alert("Comment Inserted Successfully");
			redirect(to_blog_url_id($post_id));
		}
	}
	function insert_without_login()
	{
		//checkLogin();
		$full_name = $_REQUEST["full_name"];
		$email = $_REQUEST["email"];
		$website = $_REQUEST["website"];
		$comment = str_replace("'", "''", $_REQUEST["comment"]);
		if( (substr($comment, 0, 3) == "<p>" ))
		{
			$temp = substr(substr($comment, 3, strlen($comment)), 0, strlen($comment)-3);
			$comment = $temp;
			$comment = substr($comment, 0, strlen($comment)-4);
			$comment = htmlspecialchars($comment);
		}
		$post_id = $_GET["post_id"];
		$sql = 'Insert into comment (comment, post_id, `full_name`, `email`, `website`) VALUES ( \''.$comment.'\', \''.$post_id.'\',
		\''.$full_name.'\', \''.$email.'\', \''.$website.'\')';
		//debug_r($sql);
		$result = $this->sqlq($sql);
		if($result || 1)
		{
			alert("Comment Inserted Successfully");
			redirect(to_blog_url_id($post_id));
		}
	}
	function delete()
	{
		checkLogin();
		$post_id = $_GET["post_id"];
		$comment_added_by_id  = $_SESSION["users_id"];
		$comment_id = $_GET["id"];
		$filter = '';
		if($_SESSION["groups_id"] != 3 && $_SESSION["groups_id"] != 11)
		{
			$filter = ' AND comment_added_by_id = '.$_SESSION["users_id"];
		}
		$sql = 'delete from comment where comment_id = '.$comment_id.$filter;
		//debug_r($sql);
		$result = $this->sqlq($sql);
		if($result || 1)
		{
			alert("Comment deleted Successfully");
			redirect(to_blog_url_id($post_id));
		}
	}
	
	 

	function categories()
	{
		
		$sql = "select *, UNIX_TIMESTAMP(post_added_on) as po from post order by post_name";
		$result = $this->result($sql);
		
		foreach($result as $a)
		{
			$checked = '';
			if(isset($_GET["id"])	&& $_GET["id"] == $a["post_id"])
			{
				$checked = ' selected="selected"';
				//alert($checked);
			}
			echo '                    <option value="'.to_blog_url($a["post_name"]).'"'.$checked.'>'.$a["post_name"].'</option>';
			
		}
	}
	function search($key)
	{
		$sql1 = "SELECT post_name, post_id, post_added_by_id, post_image_upload, post_description 
				FROM  post 
				WHERE (
				post_name LIKE '%".$key."%'
				OR  post_name LIKE '%".$key."%'
				OR  post_description LIKE '%".$key."%'
				)";
		$result1 = $this->result($sql1);
		$total = count($result1);
		$max = 10;
	  	$first = 1;
		if(isset($_REQUEST["first"]))
		  if($_REQUEST["first"] != "")
			$first = $_REQUEST["first"];
//		$first--;
		$last = $max;
//		alert();	%$total
		$filter = 'limit '.($first-1).', '.$max;
		$sql = "$sql1
				Order by post_name
				".$filter;
		//echo $sql;
		$result = $this->result($sql);
		if(count($result)==0)
		{
			echo ' <br />No Related Ingredient Review Post Found';
			return;
		}
		foreach($result as $a)
		{
			$name = $a["post_name"];
			$id = $a["post_id"];
			$post_added_by_id = $a["post_added_by_id"];
			$added_by = get_value("users_name", "users", $post_added_by_id);
			$vid = '';
			$pic_product = $a["post_image_upload"];
			if($pic_product == "")
			{
				$pic_product = 'img/postnoimage.jpg';
			}
		//	if($_SERVER['HTTP_HOST'] != "localhost")
				$vid = '<img src="'.$pic_product.'" width="141" height="164" />';
			
			echo '<table width="666" cellspacing="5" cellpadding="0" style="border:#FF0000 thin solid">
  <tr>
    <td width="668"><table width="656" border="0" cellpadding="0" cellspacing="3">
      <tr>
        <td width="137" rowspan="4" valign="top">'.$vid.'</td>
        <td><strong>Title :</strong></td>
        <td> '.$name.'</td>
      </tr>
      <tr>
        <td width="88"><strong>Description : </strong></td>
        <td width="438">'.substr(strip_tags(htmlspecialchars_decode1($a["post_description"])), 0, 400).'....'.'</td>
      </tr>
      <tr>
        <td><strong>Added By : </strong></td>
        <td>'.$added_by.'</td>
      </tr>
      <tr>
        <td colspan="2" align="left">
		<a href="'.to_blog_url($a["post_name"]).'">
          <input style="height:27px; padding:3px 5px 5px 5px; padding-top:3px; width:75px;  border:0px; background-color:#ff3737; color:#FFFFFF" type="button" name="button2" id="button2" value="Show" />
          </a></td>
      </tr>
    </table></td>
  </tr>
</table>
<br />';
		}
		$end = ($first-1+$max);
		$link = '?keyword='.$key.'&type[]=blog&first=';
		if($total == 0)
		{
			$first = 0;
			return;
		}
		if($end>$total)
		{
			$end = $total;
		}
		$range = $first.'-'.$end;
		//echo "\$total = $total<br />\$max = $max<br />\$first = $first<br />\$end = $end";
		echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="small_font">
				<tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="td" style="padding-top:12px;">Displaying '.$range.' of '.$total.'</td>
              <td><table border="0" align="right" cellpadding="0" cellspacing="0">
                <tr class="header">
                  <td width="85" valign="middle" class="header">';
				  if($first>1)
				  {
				  echo '
                  <div id="previous">
                    <a href="'.$link.($first-$max).'">&laquo; Previous
                    </a></div>';
				  }
				  $width = 280/7*$total;
					echo '
                  </td>
                  <td width="'.$width.'" align="left" valign="middle"><ul id="pagination-flickr">';
					for($i=1, $j=1;$i<=$total && $i<8;$j++)
					{
						if($i!=$first)
							echo '<li><a href="'.$link.$i.'">'.$j.'</a></li>';
						else
							echo '<li class="active"><a>'.$j.'</a></li>';
						$i+=$max;
					}
					
					if($j>($maxpages-1))
					{
						//echo '<li><a href="'.$link.($max*$maxpages-$max+1).'">.....</a></li>';
					}
					echo '
                  </ul></td>
                  <td width="33" valign="middle" class="header">&nbsp;</td>
                  <td width="58" valign="middle" class="header">';
				if($total>($max+$first-1))
				  {
					echo '
                  <div id="next">
                  <a href="'.$link.($first+$max).'">Next &raquo;</a>
                  </div>';
				  }
					echo '
                  </td>
                </tr>
              </table></td>
            </tr>
          </table></td>
              </tr>
			  </table>
			  ';
	}
	
	function recent()
	{
		
		$sql = "select *, UNIX_TIMESTAMP(post_added_on) as po from post ORDER BY post_added_on DESC 
LIMIT 0 , 5
";
		$result = $this->result($sql);
		$temp = '<div class="text">
                	';
		foreach($result as $a)
		{
			$temp .= '<h2><a href="'.to_blog_url($a["post_name"]).'">'.$a["post_name"].'</a></h2>
					'.substr(strip_tags(htmlspecialchars_decode1($a["post_description"])), 0, 100).'......
                    <br /><br />';
		}
		$temp .= '
                </div>';
		return $temp;
	}
	
	function top_commentators()
	{	
		$sql = "select count(*) as TOT, post_id from comment group by post_id ORDER BY TOT DESC LIMIT 0, 5";
		$result = $this->result($sql);
		
		foreach($result as $a)
		{
			$post = get_tuple('post', $a["post_id"], $key = "post_id");
			if($post["post_name"])
			{
				echo '<li>
				<a href="'.to_blog_url($post["post_name"]).'">'.$post["post_name"].'('.$a["TOT"].')'.'</a></li>';
			}
		}
	}
}
?>