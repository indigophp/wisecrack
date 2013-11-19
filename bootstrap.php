<?php

$menu = \Menu_Admin::instance('indigo');

$menu->add(array(
	array(
		'name' => 'Wisecrack',
		'url' => 'admin/wisecrack',
		'icon' => 'fa fa-quote-right',
		'sort' => 99.1,
	),
));