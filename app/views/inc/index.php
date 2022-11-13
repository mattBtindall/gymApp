<?php require APP_ROOT . '/views/inc/header.php'; ?>

<div class="p-5 mb-4 bg-light rounded-3 text-center mt-3">
    <h2><?= $data['title']; ?></h2>
    <p><?= $data['description']; ?></p>
    <div>
        <a href="<?= URL_ROOT . '/' . AREA ?>/" class="btn btn-outline-dark btn-lg">Login</a>
        <a href="<?= URL_ROOT . '/' . AREA ?>/" class="btn btn-outline-dark btn-lg">Register</a>
    </div>
</div>

<?php require APP_ROOT . '/views/inc/footer.php'; ?>