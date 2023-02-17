<?php require APP_ROOT . '/views/inc/header.php'; ?>
    <div class="activity-container card card-body bg-light mt-5 w-75">
        <div class="lead">
            <h2>Daily acitivty</h2>
            <div class="form-group">
                <label class="mb-1" for="date">Day</label>
                <!-- Date is added onto the end of this in JS -->
                <form action="<?= URL_ROOT; ?>/activitys/index/" method="POST" class="activity-form">
                    <input type="date" class="form-control form-control-lg w-auto activity-date" default="today">
                </form>
            </div>
        </div>
        <div class="activity-output py-3">
            <?php foreach($data['activity'] as $userActivity): ?>
                <?php
                    $statusClass = str_contains($userActivity['status'], 'granted') ? 'success' : 'danger';
                    $termDisplay = '';
                    if ($userActivity['membership_status'] === 'active') $termDisplay = $userActivity['term_display_name'];
                    else if ($userActivity['membership_status'] === 'expired') $termDisplay = 'expired - ' . formatForOutput($userActivity['membership_expiry_date']);
                    else $termDisplay = 'future membership, starts on - ' . formatForOutput($userActivity['membership_start_date']);
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
                        <div class="col-3">
                            <div class="h-100 d-flex align-items-center"><?= $userActivity['time']; ?></div>
                        </div>
                        <div class="col-3">
                            <div class="h-100 d-flex align-items-center text-<?= $statusClass; ?>"><?= $userActivity['status']; ?></div>
                        </div>
                        <div class="col-3">
                            <div class="h-100 d-flex align-items-center text-<?= $statusClass; ?>"><?= $termDisplay; ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
