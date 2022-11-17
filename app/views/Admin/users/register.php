<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <?php flash('register_failed'); ?>
            <h2>Create a business account</h2>
            <p>Please fill out this form to register with us:</p>
            <form action="<?= URL_ROOT; ?>/users/register" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="name">Business Name: <sup class="text-danger">*</sup></label>
                            <input type="text" name="name" class="form-control form-control-lg <?= (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['name']; ?>">
                            <span class="invalid-feedback"><?= $data['name_err']; ?></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="email">Email Address: <sup class="text-danger">*</sup></label>
                            <input type="email" name="email" class="form-control form-control-lg <?= (!empty($data['email_Err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['email']; ?>">
                            <span class="invalid-feedback"><?= $data['email_err']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="phone_number">Phone Number: <sup class="text-danger">*</sup></label>
                            <input type="tel" name="phone_number" class="form-control form-control-lg <?= (!empty($data['phone_number_rr'])) ? 'is-invalid' : ''; ?>" value="<?= $data['phone_number']; ?>">
                            <span class="invalid-feedback"><?= $data['phone_number_rr']; ?></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="est">Year Established: <sup class="text-danger">*</sup></label>
                            <input type="number" name="est" class="form-control form-control-lg <?= (!empty($data['est_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['est']; ?>">
                            <span class="invalid-feedback"><?= $data['est_err']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="password">Password: <sup class="text-danger">*</sup></label>
                            <input type="password" name="password" class="form-control form-control-lg <?= (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['password']; ?>">
                            <span class="invalid-feedback"><?= $data['password_err']; ?></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="confirm_password">Confirm Password: <sup class="text-danger">*</sup></label>
                            <input type="password" name="confirm_password" class="form-control form-control-lg <?= (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['confirm_password']; ?>">
                            <span class="invalid-feedback"><?= $data['confirm_password_err']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-2">
                    <label for="description">Business Description: <sup class="text-danger">*</sup></label>
                    <textArea name="description" class="form-control form-control-lg <?= (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['description']; ?>" style="resize: none;"></textArea>
                        <span class="invalid-feedback"><?= $data['description_err']; ?></span>
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <input type="submit" value="register" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="<?= URL_ROOT; ?>/users/login" class="btn btn-light btn-block">Have an account? Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>