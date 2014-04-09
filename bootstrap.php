<?php

$menu = \Menu_Admin::instance('indigo');

$menu->add(array(
	'name' => 'Wisecrack',
	'url' => \Uri::admin(false).'wisecrack',
	'icon' => 'fa fa-quote-right',
	'sort' => 99.1,
), true);
