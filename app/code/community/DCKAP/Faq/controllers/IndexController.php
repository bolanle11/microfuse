<?php
class DCKAP_Faq_IndexController extends Mage_Core_Controller_Front_Action
{
	private static $_signupUrl = "https://www.google.com/recaptcha/admin";
	private static $_siteVerifyUrl =
	"https://www.google.com/recaptcha/api/siteverify?";
	private $_secret;
	private static $_version = "php_1.0";

	public function indexAction()
	{
		$faqEnable= Mage::getStoreConfig('faq/faq_group/faq_select');
		if($faqEnable){
			$this->_title(Mage::helper('faq')->__("Frequently Asked Questions"));
			$this->loadLayout();
			$this->renderLayout();
		}
		else{
			$this->norouteAction();
		}
	}
	public function clickAction()
	{
		$id = $this->getRequest()->getParam('id');
		$model = Mage::getModel('faq/faq')->load($id);
		if($model->getViews()){
			$views = $model->getViews() + 1;
		}else{
			$views = 1;
		}
		$model->setViews($views);
		$model->save();
	}
	public function proceedAction()
	{
		$pass = 0;
		$question = $this->getRequest()->getParam('question');
		$category = $this->getRequest()->getParam('category');
		$answers = array();
	      $answers["success"] = false;
		if(Mage::getStoreConfig('faq/faq_recaptcha/faq_recaptchaselect')) {
		
		$captcha = $this->getRequest()->getParam('g-recaptcha-response');
		$secret = Mage::getStoreConfig('faq/faq_recaptcha/faq_secretkey');
		
		$response = null;

		$path = self::$_siteVerifyUrl;
		$data = array (
			'secret' => $secret,
			'remoteip' => $_SERVER["REMOTE_ADDR"],
			'v' => self::$_version,
			'response' => $captcha
		);
		$req = "";
		foreach ($data as $key => $value) {
			$req .= $key . '=' . urlencode(stripslashes($value)) . '&';
		}
		// Cut the last '&'
		$req = substr($req, 0, strlen($req)-1);
		$response = file_get_contents($path . $req);
		
		$answers = json_decode($response, true);
		}else{
			$pass = 1;
		}
		if (trim($answers['success']) == true || $pass == 1) {
			date_default_timezone_set(Mage::getStoreConfig("general/locale/timezone")); 
			$time = date("Y-m-d H:i:s");
			$ucustomer = 1;$cname="";$cemail="";$customerID="";
			if(Mage::getSingleton('customer/session')->isLoggedIn()){
				$ucustomer = 2;
				$customer = Mage::getSingleton('customer/session')->getCustomer();
			    $customerData = Mage::getModel('customer/customer')->load($customer->getId());
				$customerID = $customerData->getId();
				$cname = $customerData->getFirstname() . ' ' . $customerData->getLastname();
				$cemail = $customerData->getEmail();
			}	
			if($this->getRequest()->getParam('guest-email')){
				$cname = $this->getRequest()->getParam('guest-name');
				$cemail = $this->getRequest()->getParam('guest-email');
			}
			
			try{
				$model = Mage::getModel('faq/faq');
				$model->setQuestion($question);
				$model->setStatus(2);
				$model->setStoreSet("");
				$model->setByuser($ucustomer);
				$model->setCustomerId($customerID);
				$model->setCustomerName($cname);
				$model->setCustomerEmail($cemail);
				$model->setCategory($category);
				$model->setStoreId(Mage::app()->getStore(true)->getId());
				$model->setCreatedTime($time);
				$model->setUpdateTime($time);
				$model->save();
				Mage::getModel('adminnotification/inbox')->addNotice("DCKAP FAQ: New Question(ID:".$model->getId().") is added by a guest user at ".$time.".","New Question is added by a unknown guest user. Please review and update the question with answer.");
				echo 1;
			}
			catch(Exception $e){
				echo $e;
			}
		} else {
			echo 2;
		}
	}
	
}
