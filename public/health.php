<?php
echo "PHP Version: " . PHP_VERSION . "\n";
echo "PDO available: " . (class_exists('PDO') ? "yes" : "no") . "\n";
echo "PDO MySQL: " . (in_array('mysql', PDO::getAvailableDrivers()) ? "yes" : "no") . "\n";
echo "Base path: " . realpath(__DIR__ . '/..') . "\n";
