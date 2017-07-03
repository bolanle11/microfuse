<?php
class DCKAP_Faq_Model_Category extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('faq/category');
	}
}