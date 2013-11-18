<?php

namespace Wisecrack;

class Controller_Admin extends \Admin\Controller_Admin_Skeleton
{
	public function action_index()
	{
		$this->template->content = $this->theme->view('wisecrack/list');
	}

	public function action_create()
	{
		$this->template->content = $this->theme->view('wisecrack/create');
	}

	public function post_create()
	{
		$val = $this->validation();

		if ($val->run() === true)
		{
			Model_Wisecrack::forge($val->validated())->save();
			\Session::set_flash('success', gettext('Wisecrack successfully created.'));
			return \Response::redirect('admin/wisecrack');
		}
		else
		{
			$this->template->content = $this->theme->view('wisecrack/create');
			$this->template->content->model = $val->input();
			$this->template->content->set('val', $val, false);
			\Session::set_flash('error', gettext('There were some errors.'));
		}
	}

	public function action_details($id = null)
	{
		$model = $this->find($id);
		$this->template->content = $this->theme->view('wisecrack/details');
		$this->template->content->set('model', $model, false);
	}

	public function action_edit($id = null)
	{
		$model = $this->find($id);
		$this->template->content = $this->theme->view('wisecrack/edit');
		$this->template->content->set('model', $model, false);
	}

	public function post_edit($id = null)
	{
		$model = $this->find($id);
		$val = $this->validation();

		if ($val->run() === true)
		{
			$model->set($val->validated())->save();
			\Session::set_flash('success', gettext('Wisecrack successfully updated.'));
			return \Response::redirect('admin/wisecrack/details/' . $id);
		}
		else
		{
			$this->template->content = $this->theme->view('wisecrack/edit');
			$this->template->content->model = $val->input();
			$this->template->content->set('val', $val, false);
			\Session::set_flash('error', gettext('There were some errors.'));
		}
	}

	public function action_delete($id = null)
	{
		$model = $this->find($id);

		if ($model->delete())
		{
			\Session::set_flash('success', gettext('Wisecrack successfully deleted.'));
			return \Response::redirect('admin/wisecrack');
		}
		else
		{
			\Session::set_flash('error', gettext('Could not delete wisecrack.'));
			return \Response::redirect_back();
		}
	}

	protected function validation($instance = null)
	{
		$val = parent::validation($instance);
		$val->add_field('title', gettext('Title'), 'required|trim');
		$val->add_field('by', gettext('By'), 'required|trim');
		$val->add_field('body', gettext('Body'), 'required');

		return $val;
	}

	protected function translate()
	{
		return array(
			'create' => array(
				'access' => gettext('You are not authorized to paste wisecrack.')
			)
		);
	}
}
