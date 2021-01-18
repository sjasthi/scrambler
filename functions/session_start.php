<?php
if (session_status() == PHP_SESSION_NONE) {
    // set the cookie params in order to find the main domain's session
    session_set_cookie_params(0, '/', '.telugupuzzles.com');
    session_start();
}