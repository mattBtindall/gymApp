<?php require APP_ROOT . '/views/inc/header.php'; ?>

<h1><?= $data['title']; ?></h1>

<div class="card">
    <div class="card-body">
        <div class="card-title"><?= $data['title']; ?></div>
        <div class="card-text"><?= $data['description']; ?></div>
        <div>
            <a href="<?= URL_ROOT . '/' . AREA ?>/" class="btn btn-primary btn-lg">Register</a>
            <a href="<?= URL_ROOT . '/' . AREA ?>/" class="btn btn-success btn-lg">Login</a>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>