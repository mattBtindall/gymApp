<?php require APP_ROOT . '/views/inc/header.php'; ?>
    <div class="activity-container card card-body bg-light mt-5 w-75">
        <div class="lead">
            <h2>Daily acitivty</h2>
            <div class="form-group">
                <label class="mb-1" for="date">Day</label>
                <input type="date" class="form-control form-control-lg w-auto">
            </div>
        </div>
        <div class="activity-output">
            <?php foreach($data as $activity): ?>
                <?php var_dump($activity); ?>
                <div class="row">
                    <div class="col-3">

                    </div>
                    <div class="col-3">

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
