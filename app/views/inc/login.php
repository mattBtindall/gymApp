<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <?php flash('register_success'); ?>
            <h2><?= AREA; ?> login</h2>
            <p>Fill out the form below to login to your account:</p>
            <form action="<?= URL_ROOT; ?>/users/login" method='POST'>
                <div class="form-group mb-2">
                    <label for="email">Email Address: <sup class="text-danger">*</sup></label>
                    <input type="email" name="email" class="form-control form-control-lg <?= (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['email']; ?>">
                    <span class="invalid-feedback"><?= $data['email_err']; ?></span>
                </div>
                <div class="form-group mb-2">
                    <label for="password">Password: <sup class="text-danger">*</sup></label>
                    <input type="password" name="password" class="form-control form-control-lg <?= (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?= $data['password']; ?>">
                    <span class="invalid-feedback"><?= $data['password_err']; ?></span>
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <input type="submit" value="login" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="<?= URL_ROOT; ?>/users/login" class="btn btn-light btn-block">Don't have an account? Register</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>