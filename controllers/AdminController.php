<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class AdminController extends \ModuleAdminController
{
	public $defaultAction = "letterMacros";

	public function actionLetterMacros()
	{
		$macros = $this->getMacros();

		Audit::add('admin','list',null,null,array('module'=>'OphCoCorrespondence','model'=>'LetterMacro'));

		$this->render('letter_macros',array(
			'macros' => $macros,
			'unique_names' => CHtml::listData($macros,'name','name'),
		));
	}

	public function actionFilterMacros()
	{
		$this->renderPartial('_macros',array('macros' => $this->getMacros()));
	}

	public function getMacros()
	{
		$criteria = new CDbCriteria;

		if (@$_GET['site_id']) {
			$criteria->addCondition('site_id = :site_id');
			$criteria->params[':site_id'] = $_GET['site_id'];
		}

		if (@$_GET['name']) {
			$criteria->addCondition('name = :name');
			$criteria->params[':name'] = $_GET['name'];
		}

		if (@$_GET['episode_status_id']) {
			$criteria->addCondition('episode_status_id = :esi');
			$criteria->params[':esi'] = $_GET['episode_status_id'];
		}

		$criteria->order = 'site_id asc, name asc';

		return LetterMacro::model()->findAll($criteria);
	}

	public function actionAddMacro()
	{
		$macro = new LetterMacro;

		$errors = array();

		if (!empty($_POST)) {
			$macro->attributes = $_POST['LetterMacro'];

			if (!$macro->validate()) {
				$errors = $macro->errors;
			} else {
				if (!$macro->save()) {
					throw new Exception("Unable to save macro: ".print_r($macro->errors,true));
				}

				Audit::add('admin','create',$macro->id,null,array('module'=>'OphCoCorrespondence','model'=>'LetterMacro'));

				$this->redirect('/OphCoCorrespondence/admin/letterMacros');
			}
		} else {
			Audit::add('admin','view',$macro->id,null,array('module'=>'OphCoCorrespondence','model'=>'LetterMacro'));
		}

		$this->render('_macro',array(
			'macro' => $macro,
			'errors' => $errors,
		));
	}

	public function actionEditMacro($id)
	{
		if (!$macro = LetterMacro::model()->findByPk($id)) {
			throw new Exception("LetterMacro not found: $id");
		}

		$errors = array();

		if (!empty($_POST)) {
			$macro->attributes = $_POST['LetterMacro'];

			if (!$macro->validate()) {
				$errors = $macro->errors;
			} else {
				if (!$macro->save()) {
					throw new Exception("Unable to save macro: ".print_r($macro->errors,true));
				}

				Audit::add('admin','update',$macro->id,null,array('module'=>'OphCoCorrespondence','model'=>'LetterMacro'));

				$this->redirect('/OphCoCorrespondence/admin/letterMacros');
			}
		} else {
			Audit::add('admin','view',$macro->id,null,array('module'=>'OphCoCorrespondence','model'=>'LetterMacro'));
		}

		$this->render('_macro',array(
			'macro' => $macro,
			'errors' => $errors,
		));
	}
}
