<div class="Wrp">
				<div class="text-center text-success" id="img">
					<img src="./tree.png" class="treeImg" alt="Tree Background Image" />
					<div class="py-4 vc_column-inner">
						<h5 id="visionheading">SCHOOL GALLERY</h5>
					</div>
				</div>
			</div>

			<div style="margin-bottom: 15px">
				<div class="gallerySlider">
					<?php 
					$sql = "SELECT events_id, image_small FROM `events` 
					order by event_date desc
					limit 0, 10";
					$data = $db->result($sql);
					foreach($data as $a)
					echo '
					<div class="g-slider">
						<img src="'.$a["image_small"].'" alt="" />
					</div>';
					?>
				</div>
			</div>
			<div style="text-align:center" class="mb-5">
				<a class="btn btn-success hover btn-small mt-25" href="gallery.php">Show More</a>
			</div>