<?php require APP_ROOT . '/views/inc/header.php'; ?>
    <div class="dashboard-title-container mt-3 mb-3">
        <h1 class="">Dashbaord </h1>
    </div>

    <div class="dashboard-content">
        <div class="row-top mb-4">
            <div class="row">
                <div class="col-4">
                    <div class="number-active-members-container d-flex flex-column justify-content-between p-3 border bg-light rounded h-100">
                        <h3>Active Members: </h3>
                        <span class="active-members-output"></span>
                        <a href="<?= URL_ROOT ?>/members" class="btn btn-success btn-lg">View all members</a>
                    </div>
                </div>
                <div class="col-8">
                    <div class="revenue-container p-3 border bg-light rounded">
                        <div class="row">
                            <div class="col-4">
                                <h3>Revenue: </h3>
                                <ul class="revenue-filters list-group">
                                    <li class="list-group-item" data-filter-value="1 week">1 Week</li>
                                    <li class="active list-group-item" data-filter-value="4 week">4 Weeks</li>
                                    <li class="list-group-item" data-filter-value="3 month">3 Months</li>
                                    <li class="list-group-item" data-filter-value="6 month">6 Months</li>
                                    <li class="list-group-item" data-filter-value="12 month">12 Months</li>
                                </ul>
                            </div>
                            <div class="col-8">
                                <div class="revenue-chart-output"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <!-- number of visits - can calculate through the activity table -->
                <div class="visits-container p-3 border bg-light rounded h-100 d-flex flex-column justify-content-between p-3 border bg-light rounded h-100">
                    <header>
                        <h3>Number of visits:</h3>
                        <div class="visits-filters">
                            <div class="d-flex">
                                <i class="bi bi-chevron-left"></i>
                                <ul>
                                    <li class="active" data-filter-value="today">today</li>
                                    <li data-filter-value="week">This week</li>
                                    <li data-filter-value="month">This month</li>
                                    <li data-filter-value="year">This year</li>
                                </ul>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </header>
                    <div class="visits-output"></div>
                    <a href="<?= URL_ROOT ?>/activitys" class="btn btn-success btn-lg">View all activity</a>
                </div>
            </div>
            <div class="col-8">
                <div class="member-overview-container p-3 border bg-light rounded h-100">
                    <header class="titles d-flex gap-3 pb-3">
                        <h3 class="active">Newest Members</h3>
                        <h3>Expiring Members</h3>
                    </header>
                    <div class="member-overview-output"></div>
                </div>
            </div>
        </div>
    </div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
