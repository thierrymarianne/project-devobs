<?php

require_once(dirname(__FILE__).'/../includes/sefi.globals.php');

$class_dumper = $class_application::getDumperClass();

// fprint( $_SESSION, $verbose_mode );

$class_dumper::log(
	__METHOD__,
	array( '[session]', $_SESSION ),
	$verbose_mode
);