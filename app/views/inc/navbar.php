<nav class="navbar navbar-expand-lg <?= (AREA === 'User') ? 'bg-light' : 'bg-secondary';  ?>">
	<div class="container">

		<a class="navbar-brand" href="<?= URL_ROOT ?>"><?= SITE_NAME; ?></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			<div class="navbar-nav">
				<a class="nav-link" href="<?= URL_ROOT; ?>">Home</a>
				<a class="nav-link" href="<?= URL_ROOT; ?>/pages/about">About</a>
			</div>

			<div class="navbar-nav ms-auto">
				<?php if (isLoggedIn()) : ?>
					<form class="d-flex search-bar-form" role="search">
						<div class="input-group">
							<input class="form-control search-bar" type="search" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
							<span class="input-group-text" id="basic-addon2">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
									<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
								</svg>
							</span>
						</div>
    				</form>

					<!-- Pop up for search bar -->
					<div class="search-bar-modal">
						<div class="container">
							<header class="border-bottom">
								<ul>
									<li class="active">All</li>
									<li>Clients</li>
									<li>Active Members</li>
								</ul>
							</header>
							<div class="search-bar-modal__output">
								<div class="empty-searchbar-modal__container">
									<span>Type a name in the search bar</span>
								</div>
							</div>
						</div>
					</div>

					<a href="<?= URL_ROOT; ?>/users/profile" class="nav-link text-dark font-weight-bold"><?= isLoggedIn() ? $_SESSION['user_name'] . ' - ' . AREA : ''; ?></a>
					<a href="<?= URL_ROOT; ?>/users/logout" class="nav-link">Logout</a>
				<?php else : ?>
					<small class="nav-link text-dark border-end"><?= AREA; ?></small>
					<a class="nav-link" href="<?= URL_ROOT; ?>/users/login">Login</a>
					<a class="nav-link" href="<?= URL_ROOT; ?>/users/register">Register</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</nav>

<div class="user-modal">
	<div class="exit-modal-container"><i class="bi bi-x-circle" style="font-size: 3rem;"></i></div>
	<header class="px-4 pt-4 pb-1">
		<div class="row">
			<div class="col-md-2">
				<div class="img-container"><img src="http://localhost/gymApp/public/img/default_img.png" alt=""></div>
			</div>
			<div class="col-md-10">
				<h3 class="name mb-1">False Name Here</h3>
				<div class="d-flex flex-wrap">
					<span class="py-1 px-2 border rounded m-1"><i class="bi bi-envelope-fill"></i><span class="email">test@test.com</span></span>
					<span class="py-1 px-2 border rounded m-1"><i class="bi bi-phone"></i><span class="phone_number">01484650980</span></span>
					<span class="py-1 px-2 border rounded m-1"><i class="bi bi-upc"></i><span class="barcode">Barode</span></span>
					<span class="py-1 px-2 border rounded m-1"><i class="bi bi-calendar-heart"></i><span class="dob">19/03/1997</span></span>
				</div>
			</div>
		</div>
	</header>
	<div class="user-modal__menu-bar bg-light px-4 py-4 border-top border-bottom">
		<div class="d-flex gap-5">
			<span>Activity</span>
			<span>Membership</span>
		</div>
	</div>
	<div class="user-modal__content">

	</div>
</div>

<!-- Template for empty searchbar -->
<template id="empty-searchbar-msg">
	<div class="empty-searchbar-msg__container">
		<span>Type a name in the search bar</span>
	</div>
</template>

<!-- Template for searchbar results -->
<template id="row">
	<div class="search-modal__row row align-items-center py-3 border-bottom">
		<div class="col-5 border-end">
			<div class="row">
				<div class="col-4 d-flex justify-content-center align-items-center">
					<div class="img-container w-75">
						<img class="row-img rounded account-link" src="" alt="">
					</div>
				</div>
				<div class="col-8">
					<div class="d-flex flex-column justify-content-center h-100">
						<span class="name fw-bold account-link"></span>
						<span class="text-overflow-ellipsis"><i class="bi bi-envelope-fill me-1"></i><span class="email"></span></span>
						<span class="text-overflow-ellipsis"><i class="bi bi-telephone-fill me-1"></i><span class="phone_number"></span></span>
						<!-- id is for js purposes [displaying user modal] -->
						<span class="id d-none"></span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-7">
			<div class="d-flex justify-content-between align-items-center">
				<p class="membership-details m-0">No Membership</p>
				<div class="btn-container">
					<div class="btn btn-outline-secondary">Log</div>
					<div class="btn btn-secondary">Sign Up</div>
				</div>
			</div>
		</div>
	</div>
</template>