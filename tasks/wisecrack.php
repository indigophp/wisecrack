<?php

namespace Fuel\Tasks;

class Wisecrack
{

	/**
	 * This method gets ran when a valid method name is not used in the command.
	 *
	 * Usage (from command line):
	 *
	 * php oil r wisecrack
	 *
	 * @return string
	 */
	public function run($args = NULL)
	{
		echo "\n===========================================";
		echo "\nRunning DEFAULT task [Wisecrack:Run]";
		echo "\n-------------------------------------------\n\n";

		/***************************
		 Put in TASK DETAILS HERE
		 **************************/
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
	public function import($file = null)
	{
		if (is_null($file))
		{
			$file = APPPATH . 'tmp/wisecracks.xml';
		}

		if ( ! is_file($file))
		{
			throw new \InvalidPathException('File not found');
		}

		$model = \File::read($file, true);

		$model = \Format::forge($model, 'xml')->to_array();

		$model = \Arr::filter_recursive($model);

		\DBUtil::truncate_table(\Wisecrack\Model_Wisecrack::table());

		foreach ($model['item'] as $wisecrack)
		{
			\Wisecrack\Model_Wisecrack::forge()->from_array($wisecrack)->save();
		}
	}

	/**
	 * This method gets ran when a valid method name is not used in the command.
	 *
	 * Usage (from command line):
	 *
	 * php oil r wisecrack:export "arguments"
	 *
	 * @return string
	 */
	public function export($file = null)
	{
		if (is_null($file) or ! is_writable(dirname($file)))
		{
			$file = APPPATH . 'tmp/wisecracks.xml';
		}

		$model = \Wisecrack\Model_Wisecrack::query()->get();
		$model = \Format::forge($model)->to_xml();

		file_put_contents($file, $model);
	}

}
/* End of file tasks/wisecrack.php */
