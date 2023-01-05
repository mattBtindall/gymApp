<?php
class User_modal_base {

    // ajax
    public function getStatus() {
        $_SESSION['user_modal_state'] = $_SESSION['user_modal_state'] ?? ['open' => false, 'user_id' => 0];
        echo json_encode($_SESSION['user_modal_state']);
    }

    public function disable() {
        $_SESSION['user_modal_state']['open'] = false;
        $_SESSION['user_modal_state']['user_id'] = 0;
    }
}
