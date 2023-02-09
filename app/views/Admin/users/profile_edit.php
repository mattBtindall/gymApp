<?php require APP_ROOT . '/views/inc/header.php'; ?>
<h1 class="mt-2">Edit Account Info</h1>
    <div class="row">
        <div class="col-lg-6">
            <h3>Account Information</h3>
            <br>
            <h4>Contact Information</h4>
            <?php
                flash('profile_update_fail');
                flash('profile_update_no_values');
            ?>
            <form action="<?= URL_ROOT; ?>/users/profile_edit/<?= $data['backend']['id'] ?>" method="POST">
                <div class="form-group mb-2">
                    <label for="name">Name:</label>
                    <input type="text" name="name" class="form-control form-control-lg" value="<?= $data['to_show']['name']; ?>">
                </div>
                <div class="form-group mb-2">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control form-control-lg" value="<?= $data['to_show']['email']; ?>">
                </div>
                <div class="form-group mb-2">
                    <label for="est">EST:</label>
                    <input type="text" name="est" class="form-control form-control-lg" value="<?= $data['to_show']['est']; ?>">
                </div>
                <div class="form-group mb-2">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" name="phone_number" class="form-control form-control-lg" value="<?= $data['to_show']['phone_number']; ?>">
                </div>
                <div class="form-group mb-2">
                    <label for="description">Description:</label>
                    <textarea class="form-control" name="description" style="resize: none;"><?= $data['to_show']['description']; ?></textarea>
                </div>
                <div class="row">
                    <div class="col-6">
                        <input type="submit" value="Update" class="btn btn-success btn-block">
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <a href="<?= URL_ROOT; ?>/users/profile_edit/<?= $data['backend']['id']; ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>