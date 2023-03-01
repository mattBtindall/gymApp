<?php

class Dashboards extends Controller {
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = $this->model('Dashboard');
    }

    public function index() {
        $this->view('/dashboard/index');
    }
}
