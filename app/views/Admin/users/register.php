<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Create a business account</h2>
            <p>Please fill out this form to register with us:</p>
            <form action="<?= URL_ROOT; ?>/users/register" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="name">Business Name: <sup class="text-danger">*</sup></label>
                            <input type="text" name="name" class="form-control form-control-lg <?= (!empty($data['nameErr'])) ? 'is-invalid' : ''; ?>" value="<?= $data['name']; ?>">
                            <span class="invalid-feedback"><?= $data['nameErr']; ?></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="email">Email Address: <sup class="text-danger">*</sup></label>
                            <input type="email" name="email" class="form-control form-control-lg <?= (!empty($data['emailErr'])) ? 'is-invalid' : ''; ?>" value="<?= $data['email']; ?>">
                            <span class="invalid-feedback"><?= $data['emailErr']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="phoneNumber">Phone Number: <sup class="text-danger">*</sup></label>
                            <input type="tel" name="phoneNumber" class="form-control form-control-lg <?= (!empty($data['phoneNumberErr'])) ? 'is-invalid' : ''; ?>" value="<?= $data['phoneNumber']; ?>">
                            <span class="invalid-feedback"><?= $data['phoneNumberErr']; ?></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="est">Year Established: <sup class="text-danger">*</sup></label>
                            <input type="number" name="est" class="form-control form-control-lg <?= (!empty($data['estErr'])) ? 'is-invalid' : ''; ?>" value="<?= $data['est']; ?>">
                            <span class="invalid-feedback"><?= $data['estErr']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="password">Password: <sup class="text-danger">*</sup></label>
                            <input type="password" name="password" class="form-control form-control-lg <?= (!empty($data['passwordErr'])) ? 'is-invalid' : ''; ?>" value="<?= $data['password']; ?>">
                            <span class="invalid-feedback"><?= $data['passwordErr']; ?></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="confirmPassword">Confirm Password: <sup class="text-danger">*</sup></label>
                            <input type="password" name="confirmPassword" class="form-control form-control-lg <?= (!empty($data['confirmPasswordErr'])) ? 'is-invalid' : ''; ?>" value="<?= $data['confirmPassword']; ?>">
                            <span class="invalid-feedback"><?= $data['confirmPasswordErr']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-2">
                    <label for="description">Business Description: <sup class="text-danger">*</sup></label>
                    <textArea name="description" class="form-control form-control-lg <?= (!empty($data['descriptionErr'])) ? 'is-invalid' : ''; ?>" value="<?= $data['description']; ?>" style="resize: none;"></textArea>
                        <span class="invalid-feedback"><?= $data['descriptionErr']; ?></span>
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