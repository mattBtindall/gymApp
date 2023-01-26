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
					<strong class=" term-display-name"></strong>
					<span class=" expiry-date"></span>
				</div>
				<div class="btn-container">
					<div class="btn btn-outline-secondary">Log</div>
					<div class="btn btn-secondary">Sign Up</div>
				</div>
			</div>
		</div>
	</div>
</template>
