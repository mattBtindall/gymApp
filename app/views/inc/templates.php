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
				<div class="membership-details m-0 d-flex flex-column">
					<strong class="term-display-name"></strong>
					<span class="fst-italic expiry-date"></span>
				</div>
				<div class="btn-container">
					<div class="btn btn-outline-secondary log-btn">Log</div>
					<div class="btn btn-secondary add-membership">Sign Up</div>
				</div>
			</div>
		</div>
	</div>
</template>

<!-- Template for user modal -->
<template id="user-modal-membership">
	<div class="border rounded mb-3 p-3 overflow-hidden user-modal__membership">
		<div class="row">
			<div class="col-12">
				<div class="d-flex justify-content-between">
					<div class="pb-3 fw-bold">
						<span class="display-name-output"></span>
						<span> - Membership</span>
						<span>(<span class="membership-status"></span>)</span>
					</div>
					<div>
						<i class="icon bi bi-caret-left-fill"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<span>From </span>
				<span class="start-date-output text-decoration-underline"></span>
				<span>to </span>
				<span class="expiry-date-output text-decoration-underline"></span>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="pb-3">
					<span>Created on: </span>
					<span class="created-at-output"></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<span>Paid - </span>
				<span class="cost-output"></span>
			</div>
			<div class="col-6">
				<div class="d-flex justify-content-end">
					<!-- Id is inserted onto the href from JavaScript -->
					<a href="<?= URL_ROOT; ?>/members/revokeMembership/" class="btn btn-danger revoke">Revoke</a>
				</div>
			</div>
		</div>
	</div>
</template>

<template id="user-modal-activity">
	<div class="row-container"> <!-- used to add styling to -->
		<div class="row">
			<div class="col-3"><div class="date-output py-2"></div></div>
			<div class="col-3"><div class="time-output py-2"></div></div>
			<div class="col-4"><div class="status-output py-2"></div></div>
		</div>
	</div>
</template>

<template id="activity-template">
	<div class="p-3 mt-3 border rounded activity-container">
		<div class="row">
			<div class="col-3">
				<div class="d-flex align-items-center">
					<div class="img-container w-25 me-3">
						<img src="" alt="">
					</div>
					<strong class="name"></strong>
				</div>
			</div>
			<div class="col-3">
				<div class="h-100 d-flex align-items-center time"></div>
			</div>
			<div class="col-3">
				<div class="h-100 d-flex align-items-center status"></div>
			</div>
			<div class="col-3">
				<div class="h-100 d-flex align-items-center term"></div>
			</div>
		</div>
		<div style="display: none;" class="id"></div>
	</div>
</template>

<!-- dashboard newest/expiring members -->
<template id="dashboard-member-row">
	<div class="relevant-member-container p-3 mb-3 border rounded ">
		<div class="d-flex justify-content-between">
			<div class="d-flex align-items-center gap-3">
				<div class="img-container" style="max-width: 50px;">
					<img src="" alt="" class="img">
				</div>
				<span class="name fw-bold"></span>
				<span class="days fst-italic fw-light"></span>
			</div>
			<div class="d-flex flex-column">
				<span class="email"></span>
				<span class="id"></span>
			</div>
		</div>
	</div>
</template>
