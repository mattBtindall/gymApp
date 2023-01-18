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

<!-- Template for adding a term -->
<template id="new-term">
	<tr>
		<form action="<?= URL_ROOT; ?>/terms/add" method="POST">
			<td>
				<input type="text" value="" class="form-control form-control-lg w-50 terms-edit__display-name" name="display_name">
			</td>
			<td>
				<select class="form-control form-control-lg" name="term" data-term-number="<?= $i; ?>">
					<option value="please_select" selected>Please select</option>
					<option value ="1 week">1 Week</option>
					<option value ="2 week">2 Weeks</option>
					<option value ="1 month">1 month</option>
					<option value ="2 month">2 Months</option>
					<option value ="3 month">3 Months</option>
					<option value ="6 month">6 Months</option>
					<option value ="9 month">9 Months</option>
					<option value ="12 month">12 Months</option>
					<option value ="24 month">24 Months</option>
					<option value ="36 month">36 Months</option>
				</select>
			</td>
			<td>
				<input type="number" step="0.05" class="form-control form-control-lg w-50 terms-edit__cost" name="cost">
			</td>
			<td class="date-created"></td>
			<td><input class="btn btn-success terms-edit__submit" data-term-number="<?= $i; ?>" type="submit" value="submit"></td>
			<td><a class="btn opacity-0" disabled>Edit</a></td>
			<td><a class="btn opacity-0" disabled>Delete</a></td>
		</form>
	</tr>
</template>