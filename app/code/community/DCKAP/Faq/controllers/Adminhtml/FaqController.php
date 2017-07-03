<?php

class DCKAP_Faq_Adminhtml_FaqController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('faq/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$faqEnable= Mage::getStoreConfig('faq/faq_group/faq_select');
		if($faqEnable){
		$this->_initAction()
			->renderLayout();
			}
		else{
			$this->_redirect('adminhtml/dashboard/index');
		}
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('faq/faq')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			$data1["store_set"]=",".$data["store_id"].",";
			if (!empty($data)) {
				$model->setData($data);
				$model->setData($data1);
			}
			Mage::register('faq_data', $model);
			
			$this->loadLayout();
			$this->_setActiveMenu('faq/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('faq/adminhtml_faq_edit'))
				->_addLeft($this->getLayout()->createBlock('faq/adminhtml_faq_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('faq')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {

					
		if(isset($data['stores'])) {
    		if(in_array('0',$data['stores'])){
       			 $data['store_id'] = '0';
    		}
    		else{
        		$data['store_id'] = implode(",", $data['stores']);
    		}
   			unset($data['stores']);
		}
	  		$data["store_set"]=",".$data["store_id"].",";
	  		$customerData = Mage::getModel('faq/faq')->load($this->getRequest()->getParam('id'));	
	  		$notified = "";
		  	if(Mage::getStoreConfig('faq/notifications/mail_enable') && !$customerData->getNotified() && $customerData->getCustomerEmail() && $data['status']){
		  		$email = Mage::getModel('core/email_template')->loadDefault(Mage::getStoreConfig('faq/notifications/emailtemplate'));

		        $question = $data["question"];
		        $receiver = $customerData->getCustomerName();
		        $sendto = $customerData->getCustomerEmail();

		        $email->setTemplateSubject(Mage::getStoreConfig('faq/notifications/mail_subject'));
		        $email->setSenderName(Mage::getStoreConfig('faq/notifications/mail_sendername'));
		        $email->setSenderEmail(Mage::getStoreConfig('faq/notifications/mail_sendermail'));
		        $faqurl = str_replace("/index.php", "", Mage::getBaseUrl())."index.php/faq";
		        $data1 = array('question' => $question,'faq_url' => $faqurl,'receiver' => $receiver);

		        $notified = $email->send($sendto, $receiver, $data1);
		    }

			$model = Mage::getModel('faq/faq');
			if($notified){
				$data['notified'] = 1;
			}		

			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	

				$model->save();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('faq')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('faq')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('faq/faq');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $questionIds = $this->getRequest()->getParam('faq');
        if(!is_array($questionIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($questionIds as $questionId) {
                    $question = Mage::getModel('faq/faq')->load($questionId);
                    $question->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($questionIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $questionIds = $this->getRequest()->getParam('faq');
        if(!is_array($questionIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($questionIds as $questionId) {
                    $question = Mage::getSingleton('faq/faq')
                        ->load($questionId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($questionIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}
	