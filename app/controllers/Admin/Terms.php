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

    public function edit($termId) {

    }

    public function delete($termId) {
        if ($this->termsModel->deleteTerm($termId)) {
            flash('term_deleted', 'Term deleted successfully');
            redirect('/terms/index');
        } else {
            flash('term_deleted', 'Term could not be deleted at this time please try again', 'alert alert-danger');
        }
    }

}