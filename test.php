<?php

use Taf\TafConfig;

require './TafConfig.php';
$taf_config = new TafConfig();
var_dump($taf_config->get_db());