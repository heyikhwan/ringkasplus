<?php

// Autoload semua file PHP di folder Helpers
foreach (glob(__DIR__ . '/*.php') as $filename) {
    require_once $filename;
}