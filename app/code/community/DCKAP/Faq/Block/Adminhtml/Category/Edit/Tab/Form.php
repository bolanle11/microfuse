<?php

class DCKAP_Faq_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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




      $fieldset = $form->addFieldset('faq_form', array('legend'=>Mage::helper('faq')->__('Category information')));
      $fieldset->addField('name', 'editor', array(
          'name'      => 'name',
          'label'     => Mage::helper('faq')->__('Category Name'),
          'title'     => Mage::helper('faq')->__('Category Name'),
          'style'     => 'width:500px; height:100px;',
          'wysiwyg'   => false,
          'required'  => true,

      ));
      
      $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('faq')->__('Category Description'),
          'title'     => Mage::helper('faq')->__('Category Description'),
         'style'     => 'width:700px;  ',
      
         'wysiwyg'   => false,
          'required'  => false,
            'note'      => Mage::helper('faq')->__('Maximum 100 Characters allowed'),
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

      $fieldset->addField('priority', 'text',
            array(
                'name'      => 'priority',
                'label'     => Mage::helper('faq')->__('Priority'),
                'class'     => 'validate-not-negative-number',                
                'required'  => false,   
               'note'      => Mage::helper('faq')->__('Ascending order priority'),
            )
        );
          $fieldset->addField('store_id', 'multiselect', array(
          'name' => 'stores[]',
          'label' => Mage::helper('faq')->__('Store View'),
          'title' => Mage::helper('faq')->__('Store View'),
          'required' => true,
          'values' => Mage::getSingleton('adminhtml/system_store')
                     ->getStoreValuesForForm(false, true),
        ));

     
      if ( Mage::getSingleton('adminhtml/session')->getCategoryData() ) {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getCategoryData());
          Mage::getSingleton('adminhtml/session')->setCategoryData(null);
      } 
      elseif(Mage::registry('category_data')) {
          $form->setValues(Mage::registry('category_data')->getData());
      }
      return parent::_prepareForm();
  }
}
