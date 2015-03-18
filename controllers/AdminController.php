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
		//For now redirect to firms because thats where the site secretary functionality will be accessed
		$this->redirect('/admin/firms');
	}

	/**
	 * Adds or edits a Site Secretary for a Firm
	 *
	 * @param null $id
	 * @throws CHttpException
	 * @throws Exception
	 */
	public function actionAddSiteSecretary($id = null)
	{
		$firmId = $id;
		$siteSecretaries = array();
		$errors = array();
		if($firmId === null && isset(Yii::app()->session['selected_firm_id']) ){
			$firmId = Yii::app()->session['selected_firm_id'];
		}

		if(Yii::app()->request->isPostRequest){
			foreach($_POST['FirmSiteSecretary'] as $i => $siteSecretaryPost){
				if(empty($siteSecretaryPost['id']) && empty($siteSecretaryPost['direct_line']) &&  empty($siteSecretaryPost['fax'])){
					//The entire row is empty, ignore it
					continue;
				}

				//Are we updating an existing object
				if($siteSecretaryPost['id'] !== ''){
					$siteSecretary = FirmSiteSecretary::model()->findByPk($siteSecretaryPost['id']);
				} else {
					$siteSecretary = new FirmSiteSecretary();
				}
				//Set to have posted attributes
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
				//Add to array so updated version can be rendered
				$siteSecretaries[] = $siteSecretary;
			}
		} else {
			//Find all of the contacts for the current firm
			$siteSecretary = new FirmSiteSecretary();
			$siteSecretaries = $siteSecretary->findSiteSecretaryForFirm($firmId);
		}
		//Add a blank one to the end of the form for adding
		$siteSecretaries[] = new FirmSiteSecretary();


		$this->render('/admin/secretary/edit', array(
			'siteSecretaries' => $siteSecretaries,
			'errors' => $errors,
		));
	}

	/**
	 * Deletes a site secretary
	 *
	 * @throws CHttpException
	 */
	public function actionDeleteSiteSecretary()
	{
		if(Yii::app()->request->isPostRequest){
			if(!isset($_POST['id'])){
				throw new CHttpException(400, 'Unable to delete Site Secretary: no ID provided');
			}
			$siteSecretary = FirmSiteSecretary::model()->findByPk($_POST['id']);
			if(!$siteSecretary){
				throw new CHttpException(404, 'Unable to delete Site Secretary: Can not find Site Secretary');
			}
			$firmId = $siteSecretary->firm_id;
			$siteSecretary->delete();
			$this->redirect('/OphCoCorrespondence/admin/addSiteSecretary/'.$firmId);
		}
		throw new CHttpException(400, 'Invalid method for delete');
	}
}