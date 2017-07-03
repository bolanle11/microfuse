<?php
class DCKAP_Faq_Model_Mysql4_Category extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('faq/category','cat_id');
	}
}