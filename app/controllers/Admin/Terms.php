<?php
class Terms extends Controller {
    public function __construct() {
        $this->termsModel = $this->model('Term');
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        } else {
            $terms = $this->termsModel->getTerms($_SESSION['user_id']);

            $data = [
                'terms' => $terms
            ];

            $this->view('/terms/index', $data);
        }
    }

    public function edit() {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        // split $_POST['term'] into term (e.g. month or week) and term_multipler (number that preceeds the term)
        $termAndMultiplier = explode(' ', $_POST['term']);
        // here we don't have errors from term or term_multiplier as they come from the drop down
        $termUpdate = [
            'display_name' => trim($_POST['display_name']),
            'term' => $termAndMultiplier[1],
            'term_multiplier' => $termAndMultiplier[0],
            'combined_term_multiplier' => $_POST['term'],
            'cost' => trim($_POST['cost']),
            'term_id' => $_POST['term_id'],
            'display_name_err' => '',
            'cost_err' => ''
        ];

        if (empty($termUpdate['display_name'])) {
            $termUpdate['display_name_err'] = 'Please enter a display name';
        }

        if (empty($termUpdate['cost'])) {
            $termUpdate['cost_err'] = 'Please enter a price';
        }

        if (empty($termUpdate['display_name_err']) && empty($termUpdate['cost_err'])) {
            if ($this->termsModel->editTerm($termUpdate)) {
                flash('term_updated', 'Term successfully updated');
            } else {
                flash('term_updated', 'Term could not be updated at this time, please try again', 'alert alert-danger');
            }
        }

        $terms = $this->termsModel->getTerms($_SESSION['user_id']);
        $data = [
            'terms' => $terms,
            'term_update' => $termUpdate
        ];

        $this->view('/terms/index', $data);
    }

    public function delete($termId) {
        if ($this->termsModel->deleteTerm($termId)) {
            flash('term_deleted', 'Term deleted successfully');
        } else {
            flash('term_deleted', 'Term could not be deleted at this time please try again', 'alert alert-danger');
        }
        redirect('/terms/index');
    }

    public function getErrorStatus() {

    }
}
