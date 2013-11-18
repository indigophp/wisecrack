<?php

namespace Wisecrack;

class Controller_Admin_Ajax extends \Admin\Controller_Rest_Datatables
{
	public function action_list()
	{
		if ( ! \Auth::has_access('wisecrack.list'))
		{
			throw new \HttpForbiddenException();
		}

		$query = Model_Wisecrack::query();

		// Column definitions
		$columns = array(
			'title',
			'by',
			'created_at',
		);

		$counts = $this->process_query($query, $columns);

		$wisecracks = $query->get();

		return array(
			'sEcho' => \Input::param('sEcho'),
			'iTotalRecords' => $counts[0],
			'iTotalDisplayRecords' => $counts[1],
			'aaData' => array_values(array_map(function($wisecrack) {
				return array(
					$wisecrack->title,
					$wisecrack->by,
					date('Y-m-d H:i', $wisecrack->created_at),
					'<div class="hidden-print btn-group btn-group-sm" style="width:100px">'.
						(\Auth::has_access('wisecrack.view_details') ? '<a href="'.\Uri::create('admin/wisecrack/details/'.$wisecrack->id).'" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span></a>' : '').
						(\Auth::has_access('wisecrack.edit') ? '<a href="'.\Uri::create('admin/wisecrack/edit/'.$wisecrack->id).'" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a>' : '').
						(\Auth::has_access('wisecrack.all') ? '<a href="'.\Uri::create('admin/wisecrack/delete/'.$wisecrack->id).'" class="btn btn-default"><span class="glyphicon glyphicon-remove" style="color:#f55;"></span></a>' : '').
					'</div>'
				);
			}, $wisecracks))
		);
	}
}