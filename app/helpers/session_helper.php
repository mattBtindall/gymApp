<?php
    session_start();

    // Flash messaging
    // When calling this function with a different number of arguments it performs a different task
    // With two or more variables the function sets the session
    // With one varaible the function displays the message
    // example - flash('register_success', 'You're now registered') <- leave last one out as we want default
    // DISPLAY IN THE VIEW echo flash('register_success');
    function flash($name = '', $message = '', $class = 'alert alert-success') {
        if (empty($name)) {
            return;
        }

        if (!empty($message) && empty($_SESSION[$name])) {
            // sets the session varaible if the function is called with a value in $message (so at least two arguments=)
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }

            if (!empty($_SESSION[$name. '_class'])) {
                unset($_SESSION[$name . '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            // outputs the message if the function is only called with one value
            $class = !empty($_SESSION[$name . '_class']) ?  $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }