<?php
/*
Plugin Name: UltraSignup Race History
Plugin URI:
Description: Pull user's account information from UltraSignup.com
Version: 0.1
Author: Jerry Abbott
*/
require_once "UltraSignupWidget.php";
add_action("widgets_init",
    function () { register_widget("UltraSignupWidget"); });