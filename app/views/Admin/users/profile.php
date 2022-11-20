<?php require APP_ROOT . '/views/inc/header.php'; ?>
    <h1 class="mt-2">My Account</h1>
    <div class="row">
        <div class="col-lg-6">
            <h3>Account Information</h3>
            <br>
            <h4>Contact Information</h4>
            <?php flash('profile_update_success'); ?>
            <p><strong>Name: </strong><?= $data->name; ?></p>
            <p><strong>Email: </strong> <?= $data->email; ?></p>
            <p><strong>EST: </strong><?=$data->est; ?></p>
            <p><strong>Phone Number: </strong><?= $data->phone_number; ?></p>
            <strong>Description:</strong><br>
            <p><?= $data->description; ?></p>
            <a href="<?= URL_ROOT; ?>/users/profile_edit/<?= $data->id; ?>" class="btn btn-secondary">Edit profile details</a>
        </div>

        <div class="col-lg-6 d-flex justify-content-lg-center">
            <div class="profile-right-container d-flex flex-column justify-content-center align-items-lg-center">
                <h3>Profile Picture</h3>
                <div class="img-container">
                    <img class="rounded" src="<?= $data->img_url; ?>" alt="">
                </div>
                <form class="mt-2" action="<?= URL_ROOT; ?>/users/profile" method="POST" enctype="multipart/form-data">
                    <div class="form-group text-center">
                        <label class="mb-2" for="file">Upload a new image</label>
                        <?php
                            flash('img_upload_failed');
                            flash('img_upload_success');
                        ?>
                        <input class="form-control mb-2" type="file" name="file">
                        <button class="btn btn-secondary w-100" type="submit" name="submit">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>