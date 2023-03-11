<header>
			<div class="background-green d-none d-lg-block">
				<div class="Wrp">
					<div class="textWrapper">
						<div class="">
							<a class="font-color separator" href="calendar.php">CALENDAR</a>
							<a class="font-color separator" href="circular.php">CIRCULAR</a>
							<a class="font-color separator" href="homework.php">HOMEWORK</a>
							<a class="font-color separator" href="akkas.php">AKKAS</a>
							<a class="font-color separator" href="prospectus.php">PROSPECTUS</a>
						</div>
						<div class="">
							<div class="margin pl-1">
								<span class="font-color">
									<?=islamic_date();?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<!-- header block  End -->
		<!-- navbar start -->

		<nav class="navbar navbar-light">
			<div class="Wrp">
				<div class="spaceBetween">
					<a href="<?php echo BASE_URL;?>">
						<img src="img/Logo.png" alt="" />
					</a>
					<button
						class="navbar-toggler"
						type="button"
						data-bs-toggle="offcanvas"
						data-bs-target="#offcanvasNavbar"
						aria-controls="offcanvasNavbar"
					>
						<span class="navbar-toggler-icon"></span>
					</button>
					<nav>
						<div class="linkWrapper">
							<div style="display: flex">
								<a href="<?php echo BASE_URL;?>" class="navLink">Home</a>
								<a href="about.php"	 class="navLink">About Us</a>
								<a href="admission.php" class="navLink">Admission</a>
								<a href="activity.php" class="navLink">Activities</a>
								<a href="gallery.php" class="navLink">Gallery</a>
								<a href="contact.php" class="navLink">Contact</a>
								<div class="dropdown d-none d-lg-block">
									<button
										class="btn btn-outline-success-search"
										type="button"
										data-toggle="dropdown"
										onclick="searchButton()"
									>
										<i class="fas fa-search"></i>
									</button>
									<ul class="dropdown-menu">
										<div class="input-group border rounded-pill p-1">
											<input
												type="search"
												placeholder="What're you searching for?"
												aria-describedby="button-addon3"
												class="form-control bg-none border-0"
											/>
											<div class="input-group-append border-0">
												<button
													id="button-addon3"
													type="button"
													class="btn btn-link text-success"
												>
													<i class="fa fa-search"></i>
												</button>
											</div>
										</div>
									</ul>
								</div>
							</div>
						</div>
					</nav>
				</div>
			</div>
			<div
				class="offcanvas offcanvas-start offCanvasHide"
				tabindex="-1"
				id="offcanvasNavbar"
				aria-labelledby="offcanvasNavbarLabel"
			>
          
          <button type="button" class="align-self-end btn-close m-3" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#bdSidebar"></button>
       

				<div class="offcanvas-body">
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="#">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="about.php">About Us</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="activity.php">Activities</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="admission.php">Admission</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="gallery.php">Gallery</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="contact.php">Contact</a>
						</li>

						<li class="nav-item">
							
						</li>

						<hr>
						<li class="nav-item">
						<a class="nav-link" href="calendar.php">Calendar</a>
							
						</li>
						
						<li class="nav-item">
						<a class="nav-link" href="circular.php">Circular</a>
							
						</li>
						
						<li class="nav-item">
						<a class="nav-link" href="homework.php">Homework</a>
							
						</li>

						<li class="nav-item">
						<a class="nav-link" href="akkas.php">AKKAS</a>
							
						</li>
							
					</ul>
					<hr>
					<form class="d-flex">
						<input
							class="form-control me-2"
							type="search"
							placeholder="Search"
							aria-label="Search"
						/>
						<button class="background-green border-0 bt btn btn-outline-secondary float-end text-light" type="submit">
							Search
						</button>
					</form>
				</div>
			</div>
		</nav>

		<!-- navbar  end -->

		<button
			type="button"
			class="btn btn-lg btn-back-to-top"
			id="btn-back-to-top"
		>
			<i class="fas fa-arrow-up"></i>
		</button>
