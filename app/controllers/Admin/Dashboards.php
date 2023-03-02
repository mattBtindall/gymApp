<?php

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

    public function getNumberOfVisits($timeFrame) {
        // $timeFrame: today, this week, this month, this year
        $dateBoundaries = $this->calculateDates($timeFrame);
        return $this->dashboardModel->getNumberOfVisits($_SESSION['user_id'], $dateBoundaries['furthestDate'], $dateBoundaries['closestDate']);
    }

    private function calculateDates($timeFrame) {
        // this works out the ranges for the dates e.g. for today date1 00:00:00 is today
        // this morning and date2 is today at 23:59:59
        $furthestDate = new DateTime();
        $closestDate = new DateTime();
        switch ($timeFrame) {
            case 'today' :
                $closestDate->setTime(23,59,59);
                break;
            case 'week' :
                $furthestDate->modify('last monday');
                break;
            case 'month' :
                $furthestDate->modify('first day of this month');
                break;
            case 'year' :
                $furthestDate->modify('first day of January');
                break;
        }

        return [
            'furthestDate' => $furthestDate->setTime(0,0,0,0)->format(SQL_DATE_TIME_FORMAT),
            'closestDate' => $closestDate->format(SQL_DATE_TIME_FORMAT)
        ];
    }
}
