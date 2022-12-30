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
				<a class="nav-link" href="<?= URL_ROOT; ?>/members/activity">Activity</a>
				<a class="nav-link" href="<?= URL_ROOT; ?>/members">Members</a>
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
