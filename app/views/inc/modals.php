<?php
	if (!isset($data['modal'])) {
		$data['modal'] = [
			'user_id' => 0,
			'term' => '',
			'start_date' => '',
			'expiry_date' => '',
			'term_err' => '',
			'start_date_err' => '',
			'expiry_date_err' => ''
		];
	}

	if (!isset($data['memberships'])) {

	}
?>

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

<div class="user-modal">
	<div class="exit-modal-container"><i class="bi bi-x-circle" style="font-size: 3rem;"></i></div>
	<header class="px-4 pt-4 pb-3">
		<div class="row">
			<div class="col-md-2">
				<div class="img-container"><img src="http://localhost/gymApp/public/img/default_img.png" alt=""></div>
			</div>
			<div class="col-md-10">
				<h3 class="name mb-1">False Name Here</h3>
				<div class="d-flex flex-wrap">
					<span class="py-1 px-2 border rounded m-1 ms-0"><i class="bi bi-envelope-fill"></i><span class="email">test@test.com</span></span>
					<span class="py-1 px-2 border rounded m-1"><i class="bi bi-phone"></i><span class="phone_number">01484650980</span></span>
					<span class="py-1 px-2 border rounded m-1"><i class="bi bi-upc"></i><span class="barcode">Barode</span></span>
					<span class="py-1 px-2 border rounded m-1"><i class="bi bi-calendar-heart"></i><span class="dob">19/03/1997</span></span>
				</div>
			</div>
		</div>
	</header>
	<div class="user-modal__menu-bar bg-light px-4 py-4 border-top border-bottom">
		<div class="d-flex gap-5">
			<span class="user-modal__menu-item" data-content-class-name="activity">Activity</span>
			<span class="user-modal__menu-item" data-content-class-name="membership">Membership</span>
			<span class="user-modal__menu-item active" data-content-class-name="add-membership">Add Membership</span>
		</div>
	</div>
	<div class="user-modal__content px-3 py-2">
		<!-- Actibity -->
		<div class="user-modal__item activity">
			<h5>Activity</h5>
		</div>

		<!-- Membership -->
		<div class="user-modal__item membership">
			<h5>Membership</h5>
		</div>

		<!-- Add membership -->
		<div class="user-modal__item add-membership active">
			<form action="<?= URL_ROOT; ?>/members" method="POST" class="d-flex flex-column justify-content-between">
				<div class="row flex-shrink-0">
					<div class="col-6">
						<div class="form-group">
							<label for="term" class="mb-1 text-bold">Term</label>
							<select class="form-control form-control-lg term <?= !empty($data['modal']['term_err']) ? 'is-invalid' : ''; ?>" name="term" data-initial-value="please_select">
								<option <?= $data['modal']['term'] === 'please_select' ? 'selected' : ''; ?> value="please_select">Please select</option>
								<option <?= $data['modal']['term'] == '1' ? 'selected' : ''; ?> value="1">1 Month</option>
								<option <?= $data['modal']['term'] === '3' ? 'selected' : ''; ?> value="3">3 Months</option>
								<option <?= $data['modal']['term'] === '6' ? 'selected' : ''; ?> value="6">6 Months</option>
								<option <?= $data['modal']['term'] === '12' ? 'selected' : ''; ?> value="12">12 Months</option>
								<option <?= $data['modal']['term'] === 'custom' ? 'selected' : ''; ?> value="custom">Custom</option>
							</select>
							<span class="invalid-feedback"><?= $data['modal']['term_err']; ?></span>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="start" class="mb-1 text-bold ">Start Date</label>
							<input type="date" class="form-control form-control-lg start_date <?= !empty($data['modal']['start_date_err']) ? 'is-invalid' : '';?>" name="start_date" value="<?= $data['modal']['start_date']; ?>" data-initial-value="">
							<span class="invalid-feedback"><?= $data['modal']['start_date_err']; ?></span>
						</div>
						<div class="form-group expiry-date <?= $data['modal']['term'] === 'custom' ? 'active' : ''; ?>">
							<label for="start" class="mb-1 text-bold">Expiry Date</label>
							<input type="date" class="form-control form-control-lg expiry_date <?= !empty($data['modal']['expiry_date_err']) ? 'is-invalid' : '';?>" name="expiry_date" value="<?= $data['modal']['expiry_date']; ?>" data-initial-value="">
							<span class="invalid-feedback"><?= $data['modal']['expiry_date_err']; ?></span>
						</div>
					</div>
				</div>
				<!-- Passes the id of the current user when adding membership -->
				<input type="hidden" class="id" name="user_id">
				<div class="row justify-content-end mt-3">
					<div class="col-6">
						<input type="submit" value="Add Membership" class="btn btn-success btn-block w-100">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
