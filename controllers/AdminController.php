<?php
/**
 * Created by PhpStorm.
 * User: petergallagher
 * Date: 17/03/15
 * Time: 14:57
 */

class AdminController  extends BaseAdminController
{
	public function actionIndex()
	{
		$this->redirect('/admin/firms');
	}

	public function actionAddSiteSecretary($firmId = null)
	{
		$siteSecretary = new FirmSiteSecretary();
		$siteSecretaries = array();
		$errors = array();
		if($firmId === null && isset(Yii::app()->session['selected_firm_id']) ){
			$firmId = Yii::app()->session['selected_firm_id'];
		}

		if(Yii::app()->request->isPostRequest){
			foreach($_POST['FirmSiteSecretary'] as $i => $siteSecretaryPost){
				$siteSecretary = new FirmSiteSecretary();
				$siteSecretary->attributes = $siteSecretaryPost;
				if(!$siteSecretary->firm_id) {
					$siteSecretary->firm_id = (int) $firmId;
				}
				if(!$siteSecretary->validate()){
					$errors = array_merge($errors, $siteSecretary->getErrors());
				} else {
					if(!$siteSecretary->save()){
						throw new CHttpException(500, 'Unable to save Site Secretary: ' . $siteSecretary->site->name);
					}
				}
				$siteSecretaries[] = $siteSecretary;
			}
		} else {
			$criteria = new CDbCriteria();
			$criteria->params = array(':firm_id' => (int) $firmId);
			$siteSecretaries = FirmSiteSecretary::model()->findAll($criteria);
		}
		//Add a blank one to the end of the form for adding
		$siteSecretaries[] = new FirmSiteSecretary();


		$this->render('/admin/secretary/edit', array(
			'siteSecretaries' => $siteSecretaries,
			'errors' => $errors,
		));
	}

	public function actionEditSiteSecretary($id)
	{

	}
}