<?php require APP_ROOT . '/views/inc/header.php'; ?>
    <h1>My Account</h1>
    <div class="row">
        <div class="col-lg-6">
            <h3>Account Information</h3>
            <br>
            <h4>Contact Information</h4>
            <p><strong>Name: </strong><?= $data->name; ?></p>
            <p><strong>Email: </strong> <?= $data->email; ?></p>
            <p><strong>Phone Number: </strong><?= $data->phone_number; ?></p>
        </div>

        <div class="col-lg-6">
            <div class="img-container">
                <img src="<?= URL_ROOT_BASE; ?>/img/default_img.png" alt="">
            </div>
        </div>
    </div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>