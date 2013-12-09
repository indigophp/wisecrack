<?php

namespace Wisecrack;

class Controller_Admin extends \Admin\Controller_Admin_Skeleton
{
	public static function _init()
	{
		static::$translate = array(
			'create' => array(
				'access' => gettext('You are not authorized to paste wisecracks.')
			),
			'view' => array(
				'access' => gettext('You are not authorized to view wisecracks.')
			),
			'edit' => array(
				'access' => gettext('You are not authorized to edit wisecracks.')
			),
			'delete' => array(
				'access' => gettext('You are not authorized to delete wisecracks.')
			)
		);

	}

	protected function view($view, $data = array(), $auto_filter = null)
	{
		switch ($this->request->action)
		{
			case 'view':
				$view = 'wisecrack/view';
				break;
			default:
				break;
		}

		return parent::view($view, $data, $auto_filter);
	}

	protected function name()
	{
		return array(
			ngettext('wisecrack', 'wisecracks', 1),
			ngettext('wisecrack', 'wisecracks', 999),
		);
	}
}
