<?php
class DCKAP_Faq_Model_Mysql4_Faq extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('faq/faq','question_id');
	}
}