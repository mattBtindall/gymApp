<?php require APP_ROOT . '/views/inc/header.php'; ?>
    <div class="activity-container card card-body bg-light mt-5 w-75">
        <div class="lead">
            <h2>Daily acitivty</h2>
            <div class="form-group">
                <label class="mb-1" for="date">Day</label>
                <input type="date" class="form-control form-control-lg w-auto">
            </div>
        </div>
        <div class="activity-output py-3">  
            <?php foreach($data['activity'] as $userActivity): ?>
                <?php
                    $statusClass = str_contains($userActivity['status'], 'granted') ? 'success' : 'danger';
                ?>
                <div class="p-3 mt-3 border border-<?= $statusClass; ?> rounded">
                    <div class="row">
                        <div class="col-3">
                            <div class="d-flex align-items-center">
                                <div class="img-container w-25 me-3">
                                    <img src="<?= $userActivity['img_url'] ;?>" alt="">
                                </div>
                                <strong><?= $userActivity['name']; ?></strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h-100 d-flex align-items-center"><?= $userActivity['time']; ?></div>
                        </div>
                        <div class="col-4">
                            <div class="h-100 d-flex align-items-center text-<?= $statusClass; ?>"><?= $userActivity['status']; ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
