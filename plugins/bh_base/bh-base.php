<?php
/**
 * Plugin Name: BanyanHill Base
 * Description: Base shared resources.
 * Version: 1.0
 * Author: BanyanHill Web Team
 */

global $db_extra;
$db_extra = new mysqli('db-bh.c2njvvwyjtyr.us-east-1.rds.amazonaws.com', 'api_extras_user', 'J0xsZ7HEeXrFvS95!', 'api_extras');
if ($db_extra->connect_error) {
 error_log("Connection failed: " . $db_extra->connect_error, 0);
}
