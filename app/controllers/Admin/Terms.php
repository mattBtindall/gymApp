<?php
class Terms extends Controller {
    private $termsModel;
    private $activeTerms;

    public function __construct() {
        $this->termsModel = $this->model('Term');
        $this->activeTerms = $this->termsModel->getActiveTerms($_SESSION['user_id']);
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        } else {
            $data = [
                'active_terms' => $this->activeTerms
            ];

            $this->view('/terms/index', $data);
        }
    }

    public function add() {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        // $termAndMultiplier = explode(' ', $_POST['term']);
        $termAdd = [
            'display_name' => trim($_POST['display_name']),
            'term' => '',
            'term_multiplier' => '',
            'combined_term_multiplier' => $_POST['term'],
            'cost' => trim($_POST['cost']),
            'display_name_err' => '',
            'term_err' => '',
            'cost_err' => ''
        ];

        $displayNames = [];
        foreach ($this->activeTerms as $term) {
            array_push($displayNames, $term['display_name']);
        }

        if (empty($termAdd['display_name'])) {
            $termAdd['display_name_err'] = 'Please enter a display name';
        }
        else if (in_array($termAdd['display_name'], $displayNames)) {
            $termAdd['display_name_err'] = 'Please enter a unique display name';
        }

        if ($termAdd['combined_term_multiplier'] === 'please_select') {
            $termAdd['term_err'] = 'Please select a term';
        }

        if (empty($termAdd['cost'])) {
            $termAdd['cost_err'] = 'Please enter a price';
        }

        if (empty($termAdd['display_name_err']) && empty($termAdd['cost_err']) ) {
            $termAndMultiplier = explode(' ', $_POST['term']);
            $termAdd['term'] = $termAndMultiplier[1];
            $termAdd['term_multiplier'] = $termAndMultiplier[0];
            if ($this->termsModel->addTerm($termAdd, $_SESSION['user_id'])) {
                flash('term_added', 'The new term has been successfully added');
            } else {
                flash('term_added', 'New term failed to upload, please try again', 'alert alert-danger');
            }
            redirect('/terms/index');
        }

        $data = [
            'term_add' => $termAdd,
            'active_terms' => $this->activeTerms
        ];


        $this->view('/terms/index', $data);
    }

    public function edit() {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $termUpdate = [
            'display_name' => trim($_POST['display_name']),
            'combined_term_multiplier' => $_POST['term'],
            'cost' => trim($_POST['cost']),
            'term_id' => $_POST['term_id'],
            'term_err' => '',
            'display_name_err' => '',
            'cost_err' => ''
        ];

        if (empty($termUpdate['display_name'])) {
            $termUpdate['display_name_err'] = 'Please enter a display name';
        }

        if ($termUpdate['combined_term_multiplier'] === 'please_select') {
            $termUpdate['term_err'] = 'Please select a term';
        }

        if (empty($termUpdate['cost'])) {
            $termUpdate['cost_err'] = 'Please enter a price';
        }

        if (empty($termUpdate['display_name_err']) && empty($termUpdate['cost_err']) && empty($termUpdate['term_err'])) {
            // split $_POST['term'] into term (e.g. month or week) and term_multipler (number that preceeds the term)
            $termAndMultiplier = explode(' ', $_POST['term']);
            $termUpdate['term'] = $termAndMultiplier[1];
            $termUpdate['term_multiplier'] = $termAndMultiplier[0];

            if ($this->termsModel->editTerm($termUpdate)) {
                flash('term_updated', 'Term successfully updated');
            } else {
                flash('term_updated', 'Term could not be updated at this time, please try again', 'alert alert-danger');
            }
            $_SESSION['term_edit_error_id'] = '';
        } else {
            // set error variable for js
            $_SESSION['term_edit_error_id'] = $termUpdate['term_id'];
        }

        $data = [
            'active_terms' => $this->termsModel->getActiveTerms($_SESSION['user_id']), // get news terms here so you get the update term to display
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
        $_SESSION['term_edit_error_id'] = $_SESSION['term_edit_error_id'] ??'';
        echo json_encode($_SESSION['term_edit_error_id']);
    }

    public function getTerms() {
        // called from javascript
        $terms = $this->activeTerms ?? '{}';
        echo json_encode($terms);
    }
}
