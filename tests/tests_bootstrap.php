<?php

/**
 * test/test_bootstrap.php
 *
 * @category TestFuncional
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require_once __DIR__ . '/../vendor/autoload.php';

mt_srand();

// Create/update tables in the test database
// Only required if functional testing is performed
// Utils::updateSchema();
