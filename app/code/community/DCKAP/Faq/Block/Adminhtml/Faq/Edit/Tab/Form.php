<?php

class DCKAP_Faq_Block_Adminhtml_Faq_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $form->setHtmlIdPrefix('faq');
      $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')
                      ->getConfig(array('tab_id' => 'form_section'));
      $wysiwygConfig["files_browser_window_url"] = 
              Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index');
      $wysiwygConfig["directives_url"] = 
              Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive');
      $wysiwygConfig["directives_url_quoted"] = 
              Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive');
      $wysiwygConfig["widget_window_url"] = 
              Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index');
      $wysiwygConfig["files_browser_window_width"] = 
              (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_width');
      $wysiwygConfig["files_browser_window_height"] = 
              (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_height');



      $fieldset = $form->addFieldset('faq_form', array('legend'=>Mage::helper('faq')->__('FAQ\'s information')));
      if(Mage::getStoreConfig('faq/faq_group/category_enabled')){
      $model  = Mage::getModel('faq/category')->getCollection();
      
      $categoryvalues[] = array(
                  'value'     => '0',
                  'label'     => '--',
              );
      foreach ($model as $category) {
          $categoryvalues[]  = array(
                  'value'     => $category->getId(),
                  'label'     => Mage::helper('faq')->__($category->getName()),
              );
      }

     $fieldset->addField('category', 'select', array(
          'name'      => 'category',
          'label'     => Mage::helper('faq')->__('Category'),
          'title'     => Mage::helper('faq')->__('Category'),
          'values'    => $categoryvalues
      ));
     }
      $fieldset->addField('question', 'editor', array(
          'name'      => 'question',
          'label'     => Mage::helper('faq')->__('Question'),
          'title'     => Mage::helper('faq')->__('Question'),
          'style'     => 'width:500px; height:100px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
      
      $fieldset->addField('answer', 'editor', array(
          'name'      => 'answer',
          'label'     => Mage::helper('faq')->__('Answer'),
          'title'     => Mage::helper('faq')->__('Answer'),
         'style'     => 'width:700px;  ',
      
         'wysiwyg'   => true,
          'required'  => true,
             'state' => 'html',
            'config' => $wysiwygConfig,
      ));

      $fieldset->addField('status', 'select', array(
          'name'      => 'status',
          'label'     => Mage::helper('faq')->__('Status'),
          'title'     => Mage::helper('faq')->__('Status'),
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('faq')->__('Enabled'),
              ),
              array(
                  'value'     => 2,
                  'label'     => Mage::helper('faq')->__('Disabled'),
              ),
          ),
      ));

      if(!Mage::getStoreConfig('faq/faq_group/viewsorter')){
        $fieldset->addField('priority', 'text',
              array(
                  'name'      => 'priority',
                  'label'     => Mage::helper('faq')->__('Priority'),
                  'class'     => 'validate-not-negative-number',                
                  'required'  => false,   
                 'note'      => Mage::helper('faq')->__('Ascending order priority'),
              )
          );
      }

      $fieldset->addField('store_id', 'multiselect', array(
          'name' => 'stores[]',
          'label' => Mage::helper('faq')->__('Store View'),
          'title' => Mage::helper('faq')->__('Store View'),
          'required' => true,
          'values' => Mage::getSingleton('adminhtml/system_store')
                     ->getStoreValuesForForm(false, true),
        ));
        if(Mage::registry('faq_data')->getCustomerId()){
          $fieldset->addField('customer_name', 'label',
            array(
                'name'      => 'customer_name',
                'label'     => Mage::helper('faq')->__('Customer Name'),
            )
        );
          $fieldset->addField('customer_id', 'label', 
            array(
                'name'      => 'customer_id',
                'label'     => Mage::helper('faq')->__('Customer ID'),
            )
        );
          $fieldset->addField('customer_email', 'label',
            array(
                'name'      => 'customer_email',
                'label'     => Mage::helper('faq')->__('Customer Email'),
            )
        );
        }
      if ( Mage::getSingleton('adminhtml/session')->getFaqData() ) {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getFaqData());
          Mage::getSingleton('adminhtml/session')->setFaqData(null);
      } 
      elseif(Mage::registry('faq_data')) {
          $form->setValues(Mage::registry('faq_data')->getData());
      }
      return parent::_prepareForm();
  }
}
