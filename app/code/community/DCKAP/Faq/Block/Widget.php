<?php
class DCKAP_Faq_Block_Widget extends Mage_Core_Block_Template
implements Mage_Widget_Block_Interface {

  protected function _toHtml() {
      return $this->getLayout()->createBlock('faq/faq')->setTemplate('faq/faq.phtml')->toHtml();
  }
}