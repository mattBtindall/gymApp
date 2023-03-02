<?php

/**
 * Used mainly for ajax calls to get data
 * this works differently to other models
 * as all the data is returned at once instead
 * of indvidual function calls
 */

class Dashboards extends Controller {
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = $this->model('Dashboard');
    }

    public function index() {
        $this->view('/dashboard/index');
    }

    public function getNumberOfActiveMembers() {
        return $this->dashboardModel->getNumberOfActiveMembers($_SESSION['user_id']);
    }
}
