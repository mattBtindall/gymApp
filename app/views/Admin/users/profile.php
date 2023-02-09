<?php require APP_ROOT . '/views/inc/header.php'; ?>
    <?php require APP_ROOT . '/views/inc/profile_content.php'; ?>
    <h3>Terms</h3>
    <div class="term-container mb-3">
        <div class="row">
            <div class="col-3">
                <strong>Display name</strong>
            </div>
            <div class="col-3">
                <strong>Term</strong>
            </div>
            <div class="col-3">
                <strong>Cost (<?= CURRENCY_SYMBOL; ?>)</strong>
            </div>
            <div class="col-3">
                <strong>Date Created</strong>
            </div>
        </div>
        <?php foreach($data['terms'] as $term): ?>
            <div class="row">
                <div class="col-3">
                    <div class="py-2"><?= $term['display_name']; ?></div>
                </div>
                <div class="col-3">
                    <div class="py-2"><?= $term['term_output']; ?></div>
                </div>
                <div class="col-3">
                    <div class="py-2"><?= $term['cost']; ?></div>
                </div>
                <div class="col-3">
                    <div class="py-2"><?= $term['created_at']; ?></div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (!$data['terms']): ?>
            <div class="d-flex justify-content-center p-3">
                <strong class="">No terms</strong>
            </div>
        <?php endif; ?>
    </div>
    <a href="<?= URL_ROOT; ?>/terms/index/" class="btn btn-secondary">Edit/Add terms</a>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>