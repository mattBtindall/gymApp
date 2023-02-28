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
                        <span class="output"></span>
                        <a href="<?= URL_ROOT ?>/members" class="btn btn-success btn-lg">View all members</a>
                    </div>
                </div>
                <div class="col-8">
                    <div class="revenue-container p-3 border bg-light rounded">
                        <div class="row">
                            <div class="col-4">
                                <h3>Revenue: </h3>
                                <ul class="revenue-filters list-group">
                                    <li class="list-group-item">1 Week</li>
                                    <li class="active list-group-item">4 Weeks</li>
                                    <li class="list-group-item">3 Months</li>
                                    <li class="list-group-item">6 Months</li>
                                    <li class="list-group-item">12 Months</li>
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
                <div class="visits-container p-3 border bg-light rounded h-100">
                    <header>
                        <h3>Number of visits:</h3>
                        <ul class="visits-filters">
                            <li>1 Week</li>
                            <li>4 weeks</li>
                            <li>3 Months</li>
                            <li>6 Months</li>
                            <li>12 Months</li>
                        </ul>
                    </header>
                    <div class="visits-chart-output"></div>
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
