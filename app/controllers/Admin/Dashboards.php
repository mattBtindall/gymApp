<?php

class Dashboards extends Controller {
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = $this->model('Dashboard');
    }

    public function index() {
        $this->view('/dashboard/index');
    }

    public function getRelevantMembers() {
        return [
            'recentMembers' => $this->getRecentMembers($_SESSION['user_id']),
            'expiringMembers' => $this->dashboardModel->getExpiringMembers($_SESSION['user_id'])
        ];
    }

    private function getRecentMembers($adminId) {
        $recentMembers = $this->dashboardModel->getRecentMembers($adminId);
        $memberIds = [];
        // remove any duplicated user_ids
        $filteredRecentMembers = array_filter($recentMembers, function($member) use(&$memberIds) {
            if (!in_array($member['user_id'], $memberIds)) {
                $memberIds[] = $member['user_id'];
                return true;
            }
            return false;
        });
        return $filteredRecentMembers;
    }

    public function getNumberOfActiveMembers() {
        return $this->dashboardModel->getNumberOfActiveMembers($_SESSION['user_id']);
    }

    public function getNumberOfVisits($timeFrame) {
        // $timeFrame: today, this week, this month, this year
        $dateBoundaries = $this->calculateDates($timeFrame);
        $current = $this->dashboardModel->getNumberOfVisits($_SESSION['user_id'], $dateBoundaries['current']['furthestDate'], $dateBoundaries['current']['closestDate']);
        $comparison = $this->dashboardModel->getNumberOfVisits($_SESSION['user_id'], $dateBoundaries['comparison']['furthestDate'], $dateBoundaries['comparison']['closestDate']);
        $percentageDifference = $this->calculateDifference($current, $comparison);
        return [
            'current' => $current,
            'percentageDifference' => $percentageDifference
        ];
    }

    private function calculateDates($timeFrame) {
        // this works out the ranges for the dates e.g. for today date1 00:00:00 is today
        // this morning and date2 is today at the current time
        $current = [
            'furthestDate' => new DateTime(),
            'closestDate' => new DateTime()
        ];
        $comparison = [
            'furthestDate' => new DateTime(),
            'closestDate' => new DateTime()
        ];

        switch ($timeFrame) {
            case 'today' :
                $comparison['furthestDate']->modify('-1 day');
                $comparison['closestDate']->modify('-1 day');
                break;
            case 'week' :
                if ($current['furthestDate']->format('l') !== 'Monday') {
                    $current['furthestDate']->modify('last monday');
                    $comparison['furthestDate']->modify('-2 monday');
                    $comparison['closestDate']->modify('-1 week');
                } else {
                    $comparison['furthestDate']->modify('last monday');
                    $comparison['closestDate']->modify('last monday');
                }
                break;
            case 'month' :
                $current['furthestDate']->modify('first day of this month');
                $comparison['furthestDate']->modify('first day of last month');
                $comparison['closestDate']->modify('-1 month');
                break;
            case 'year' :
                $current['furthestDate']->modify('first day of January');
                $comparison['furthestDate']->modify('first day of January ' . ($comparison['furthestDate']->format('Y') - 1) . ' 00:00:00');
                $comparison['closestDate']->modify('-1 year');
                break;
        }

        $current['furthestDate'] = $current['furthestDate']->setTime(0,0,0,0)->format(SQL_DATE_TIME_FORMAT);
        $current['closestDate'] = $current['closestDate']->format(SQL_DATE_TIME_FORMAT);
        $comparison['furthestDate'] = $comparison['furthestDate']->setTime(0,0,0,0)->format(SQL_DATE_TIME_FORMAT);
        $comparison['closestDate'] = $comparison['closestDate']->format(SQL_DATE_TIME_FORMAT);

        return [
            'current' => $current,
            'comparison' => $comparison
        ];
    }

    private function calculateDifference($newValue, $oldValue) {
        // calculates the percentage difference of two numbers
        $value = ($newValue - $oldValue) / (($newValue + $oldValue) / 2) * 100;
        echo round($value, 2);
    }

    public function getRevenue($timeFrame) {
        // timeFrame can be: '1 week', '4 week', '3 month', '6 month', '12 month'
        return $this->dashboardModel->getRevenue($timeFrame, $_SESSION['user_id']);
    }
}
