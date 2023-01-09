<h1 class="mt-2">My Account</h1>
<div class="row">
    <div class="col-lg-6">
        <h3>Account Information</h3>
        <br>
        <h4>Contact Information</h4>
        <?php
            flash('profile_update_success');
            flash('incorrect_area');
        ?>
        <?php
            foreach ($data['to_show'] as $key => $value) {
                echo "<p><strong>{$key}: </strong>{$value}</p>";
            }
        ?>
        <a href="<?= URL_ROOT; ?>/users/profile_edit/<?= $data['backend']['id']; ?>" class="btn btn-secondary">Edit profile details</a>
    </div>

    <div class="col-lg-6 d-flex justify-content-lg-center">
        <div class="profile-right-container d-flex flex-column justify-content-center align-items-lg-center">
            <h3>Profile Picture</h3>
            <div class="img-container">
                <img class="rounded" src="<?= $data['backend']['img_url']; ?>" alt="">
            </div>
            <form class="mt-2" action="<?= URL_ROOT; ?>/users/profile" method="POST" enctype="multipart/form-data">
                <div class="form-group text-center">
                    <label class="mb-2" for="file">Upload a new image</label>
                    <?php
                        flash('img_upload_failed');
                        flash('img_upload_success');
                    ?>
                    <input class="form-control mb-2" type="file" name="file">
                    <button class="btn btn-secondary w-100 upload" type="submit" name="submit" disabled>Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>