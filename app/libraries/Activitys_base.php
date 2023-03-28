<?php
class Activitys_base extends Controller {
    private $queryMethod;
    private $activityModel;

    public function __construct($queryMethod) {
        $this->queryMethod = $queryMethod;
        $this->activityModel = $this->model('Activity');
    }

    public function index($date = NULL) {
        $date = $date ?? 'NOW()';
        $data['activity'] = $this->activityModel->{$this->queryMethod}($_SESSION['user_id'], $date);
        foreach ($data['activity'] as &$userActivity) {
            $userActivity = formatActivity($userActivity);
        }
        $this->view('/activity/index', $data);
    }
}
