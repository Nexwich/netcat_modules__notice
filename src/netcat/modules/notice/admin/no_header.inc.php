<?php

/**
 * Script that can be included to initialize NetCat scripts and check user rights
 * (e.g. in scripts that should output JSON data)
 */

$NETCAT_FOLDER = realpath(dirname(__FILE__) . "/../../../../") . "/";
include_once $NETCAT_FOLDER . "vars.inc.php";
require_once $ADMIN_FOLDER . "function.inc.php";