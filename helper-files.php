<?php

// Include all general purpose helper functions
foreach (glob(__DIR__.'/helpers/*.php') as $file) {
    require_once $file;
}
