<!-- <div style="margin-bottom: 50px;">
  <div class="container">
    <div class="row ">
      <div class="col-lg-4 col-12 spacing">
        <div class="image">
          <div id="zoom-In">
            <figure>
              <img src="img/image1.jpeg" alt="Newsletter February, 2022" class="img-thumbnail ">
            </figure>
          </div>
        </div>

      </div>
      <div class="col-lg-4 col-12 spacing">

        <div class="image">
          <div id="zoom-In">
            <figure>
              <img src="img/image2.jpeg" alt="Akkas Issue 11 Jan, 2020" class="img-thumbnail ">
            </figure>
          </div>
        </div>

      </div>
      <div class="col-lg-4 col-12 spacing">
        <div class="image">
          <div id="zoom-In">
            <figure>
              <img src="img/image3.jpeg" alt="Prospectus" class="img-thumbnail ">
            </figure>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
 -->
<?php
$sql = "select * from announcement where type = 'akkas' ORDER BY added_on DESC limit 0, 1";
$akkas = $db->result($sql, 1);
$names = explode("-", $akkas["name"]);

// $sql = "select * from announcement where type = 'newsletter' ORDER BY added_on DESC limit 0, 1";
// $newsletter = $db->result($sql, 1);
// // debug_r($newsletter);
// $names = explode("-", $akkas["name"]);
echo '
<div style="margin-bottom: 30px;">
				<div class="Wrp">
					<div class="imgWrapper">
						<a href="downloads/2022/newsletter_november_2022.pdf" target="_blank">
							<div class="featureImage">
								<div id="zoom-In">
									<img src="img/image1.png" alt="..." />
								</div>
								<p><b>NEWSLETTER</b><br />Volume 8, Issue 1<br />November 2022</p>
							</div>
            </a>
						<a href="'.$akkas['file_upload'].'" target="_blank">
							<div class="featureImage">
								<div id="zoom-In">
									<img src="img/image2.png" alt="..." class="" />
								</div>
								<p><b>'.$names[0].'</b><br />'.$names[1].'<br />'.$names[2].'</p>
							</div>
            </a>
						<a href="announcement_images/Prospectus2023.pdf" target="_blank">
							<div class="featureImage">
								<div id="zoom-In">
									<img src="img/image3.png" alt="..." class="" />
								</div>
								<p><b>PROSPECTUS</b><br />2023-2024</p>
							</div>
            </a>
					</div>
				</div>
			</div>';