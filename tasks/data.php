<?php

namespace Fuel\Tasks;

class Data
{
	protected $formats = array(
		'xml',
		'csv',
		'json',
		'serialized',
	);

	/**
	 * This method gets ran when a valid method name is not used in the command.
	 *
	 * Usage (from command line):
	 *
	 * php oil r wisecrack::data
	 *
	 * @return string
	 */
	public function run()
	{
		$args = func_get_args();
		return call_user_func_array(array($this, 'export'), $args);
	}


	/**
	 * This method gets ran when a valid method name is not used in the command.
	 *
	 * Usage (from command line):
	 *
	 * php oil r wisecrack:import "arguments"
	 *
	 * @return string
	 */
	public function import($file = null, $format = null)
	{
		if (is_null($file))
		{
			$file = APPPATH . 'tmp/wisecracks.xml';
		}

		if ( ! is_file($file))
		{
			throw new \InvalidPathException('File not found');
		}

		$ext = substr(strrchr($file, '.'), 1);

		if (is_null($format) and ! in_array($ext, $this->formats))
		{
			\Cli::error('You passed a file with an unsupported extension. Please specify a format.');
			return;
		}

		$format = $ext;

		$model = \File::read($file, true);

		$model = \Format::forge($model, $format)->to_array();

		$format == 'xml' and $model = reset($model);

		$model = \Arr::filter_recursive($model);

		\DBUtil::truncate_table(\Wisecrack\Model_Wisecrack::table());

		foreach ($model as $wisecrack)
		{
			\Wisecrack\Model_Wisecrack::forge()->from_array($wisecrack)->save();
		}
	}

	/**
	 * This method gets ran when a valid method name is not used in the command.
	 *
	 * Usage (from command line):
	 *
	 * php oil r wisecrack::data:export [file]
	 *
	 * @return string
	 */
	public function export($file = null, $format = null)
	{
		if (is_null($file) or ! is_writable(dirname($file)))
		{
			$file = APPPATH . 'tmp/wisecracks.xml';
		}

		$ext = substr(strrchr($file, '.'), 1);

		if (is_null($format) and ! in_array($ext, $this->formats))
		{
			\Cli::error('You passed a file with an unsupported extension. Please specify a format.');
			return;
		}

		$format = $ext;

		$model = \Wisecrack\Model_Wisecrack::query()->get();
		$model = \Format::forge($model)->to_array();
		$model = \Format::forge($model)->{'to_' . $format}();

		file_put_contents($file, $model);
	}

	/**
	 * Help
	 *
	 * Usage (from command line):
	 *
	 * php oil r wisecrack::data:help
	 *
	 * @return string
	 */
	public function help()
	{
		\Cli::write('Wisecrack data import/export');

		$output = <<<HELP

Usage:
	php oil [r|refine] wisecrack::data[:task] [file] [format]

Task: export or import (default is export)
File: Writable path, where a file can be created or file to be updated.
Format: You HAVE TO pass it when you pass a file with and unsupported. See Formats for details.

Description:
	Import or export wisecrack data

Formats:
	Valid export/input file formats:
		* xml
		* csv
		* json
		* php
		* serialized
HELP;

		\Cli::write($output);
	}
}
/* End of file tasks/wisecrack.php */
