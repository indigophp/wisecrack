<?php

namespace Wisecrack;

class Model_Wisecrack extends \Orm\Model
{
	use \Admin\Model_Skeleton;

	protected static $_belongs_to = array(
		'creator' => array(
			'model_to' => 'Model\\Auth_User',
			'key_from' => 'created_by',
		),
		'updater' => array(
			'model_to' => 'Model\\Auth_User',
			'key_from' => 'updated_by',
		),
	);

	protected static $_properties = array(
		'id' => array(
			'data_type' => 'int',
			'view' => false
		),
		'title' => array(
			'data_type' => 'text',
			'form' => array('type' => 'text'),
			'validation' => array('required', 'trim'),
			'list' => array('type' => 'text'),
		),
		'by' => array(
			'data_type' => 'text',
			'form' => array('type' => 'text'),
			'validation' => array('required', 'trim'),
			'list' => array('type' => 'select'),
		),
		'body' => array(
			'data_type' => 'textarea',
			'form' => array('type' => 'textarea'),
			'validation' => array('required'),
		),
		'created_at' => array(
			'data_type' => 'time_unix',
			'data_format' => 'mysql_date',
			'form' => array(
				'type' => false,
			),
			'list' => array('type' => 'text'),
		),
		'updated_at' => array(
			'data_type' => 'time_unix',
			'form' => array(
				'type' => false,
			),
		),
		'created_by' => array(
			'data_type' => 'int',
			'form' => array('type' => false),
		),
		'updated_by' => array(
			'data_type' => 'int',
			'form' => array('type' => false),
		),
		'creator.fullname'  => array(
			'eav' => 'metadata',
			'list' => array('type' => 'text'),
		),
		'updater.fullname'  => array(
			'eav' => 'metadata',
		),
	);

	protected static $_observers = array(
		'Orm\\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
		'Orm\\Observer_CreatedBy' => array(
			'events' => array('before_insert'),
		),
		'Orm\\Observer_UpdatedBy' => array(
			'events' => array('before_update'),
		),
		'Orm\\Observer_Validation' => array(
			'events' => array('before_save')
		),
		'Indigo\\Orm\\Observer_Typing',
	);

	protected static $_table_name = 'wisecracks';

	public static function _init()
	{
		static::$_properties = \Arr::merge(static::$_properties, array(
			'id' => array(
				'label' => gettext('ID')
			),
			'title' => array(
				'label' => gettext('Title'),
			),
			'by' => array(
				'label' => gettext('By'),
				'list' => array(
					'options' => function() {
						return \DB::select('by', 'by')->from(static::table())->group_by('by')->execute()->as_array('by', 'by');
					},
				)
			),
			'body' => array(
				'label' => gettext('Body'),
			),
			'created_at' => array(
				'label' => gettext('Created At'),
			),
			'updated_at' => array(
				'label' => gettext('Updated At'),
			),
			'created_by' => array(
				'label' => gettext('Created By'),
			),
			'updated_by' => array(
				'label' => gettext('Updated By'),
			),
			'creator.fullname'  => array('label' => gettext('Created By')),
			'updater.fullname'  => array('label' => gettext('Updated By')),
		));
	}

	public function observe($event)
	{
		if ($event == 'before_insert' and ! empty($this->import))
		{
			return false;
		}
		else
		{
			return parent::observe($event);
		}
	}
}
