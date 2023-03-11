	<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="app.js"></script>
		<script
			src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
			integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
			crossorigin="anonymous"
		></script>
		<script
			src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
			integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
			crossorigin="anonymous"
		></script>
		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
			crossorigin="anonymous"
		></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script
			src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"
			integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"
		></script>
		<script type="text/javascript" src="js/dobpicker.js"></script>

		<script>
			$('.recent_activity').slick({
				infinite: true,
				arrows:true,
				slidesToShow: 4,
				slidesToScroll: 1,
				dots: false,
				responsive: [
					
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
					}
				]
				
			});


			$('.banner-image').slick({
				// speed: 300,
				dots: true,
				infinite: true,
				autoplay: true,
				slidesToShow: 1,
				autoplaySpeed: 2500,
				adaptiveHeight: true
			});
			
			$('.gallerySlider').slick({
				speed: 300,
				arrows: true,
				infinite: true,
				// autoplay: true,
				slidesToShow: 5,
				slidesToScroll: 5,
				// autoplaySpeed: 3000,
				responsive: [
					{
						breakpoint: 1024,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3,
							infinite: true
						}
					},
					{
						breakpoint: 600,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2
						}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
					}
				]
			});
		</script>

<?php
    if(page_name() == "index") {?>
        <!-- <?php echo BASE_URL; ?> -->
        <?php echo $data["html_foot"]; ?>
    <?php
    }
    else
    {?>
    <?php
    }
    ?>
