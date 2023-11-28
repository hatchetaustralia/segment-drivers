<?php

require_once './vendor/autoload.php';

use MatthiasMullie\Minify\JS;

$minifier = new JS();
$minifier->add(__DIR__.'/resources/segment.js');
$minifier->minify(__DIR__.'/resources/segment.min.js');
