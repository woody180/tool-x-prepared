<?php

use ScssPhp\ScssPhp\Compiler;
$compiler = new Compiler();

$dir = dirname(APPROOT) . '/public/assets/';
$compiler->setImportPaths(dirname(APPROOT) . '/public/assets/scss/');
$scss = file_get_contents($dir . 'scss/bootstrap.scss');
$css = $compiler->compileString($scss)->getCss();
$css = minify_css($css);

file_put_contents($dir . 'css/main.min.css', $css);