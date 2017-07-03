<?php

class DCKAP_Faq_Block_Adminhtml_Faq_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('faqGrid');
      $this->setDefaultSort('question_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);

  }

  protected function _prepareCollection()
  {
    $collection = Mage::getModel('faq/faq')->getCollection();
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('question_id', array(
          'header'    => Mage::helper('faq')->__('Question_ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'question_id',
      ));

      $this->addColumn('question', array(
          'header'    => Mage::helper('faq')->__('Question'),
          'align'     =>'left',
          'index'     => 'question',
      ));

       $this->addColumn('answer', array(
          'header'    => Mage::helper('faq')->__('Answer'),
          'align'     =>'left',
          'index'     => 'answer',
      ));
      if(Mage::getStoreConfig('faq/faq_group/category_enabled')){
        $valuess = array();
        $categoryvalues  = Mage::getModel('faq/category')->getCollection();
        foreach ($categoryvalues as $category) {
          $valuess[$category->getId()] = $category->getName();
        }
        $this->addColumn('category', array(
          'header'    => Mage::helper('faq')->__('Category'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'category',
          'type'      => 'options',
          'options'   => $valuess,
      ));
      }
      $this->addColumn('status', array(
          'header'    => Mage::helper('faq')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
      $this->addColumn('byuser', array(
          'header'    => Mage::helper('faq')->__('User/Admin'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'byuser',
          'type'      => 'options',
          'options'   => array(
              0 => 'Admin',
              1 => 'Guest',
              2 => 'Customer',
          ),
      ));
      $this->addColumn('customer_name',
            array(
                'header' => Mage::helper('faq')->__('Customer Name'),
                'width' => '50px',
                'index' => 'customer_name'
            )
        );
      if(!Mage::getStoreConfig('faq/faq_group/viewsorter')){
        $this->addColumn('priority',
            array(
                'header' => Mage::helper('faq')->__('Priority'),
                'width' => '50px',
                'index' => 'priority'
            )
        );
      }

      if (!Mage::app()->isSingleStoreMode()) {
        $this->addColumn('store_id', array(
        'header'        => Mage::helper('faq')->__('Store View'),
        'index'         => 'store_id',
        'type'          => 'store',
        'store_all'     => true,
        'store_view'    => true,
        'sortable'      => true,
        'filter_condition_callback' => array($this,
            '_filterStoreCondition'),
      )  );
    }


       $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('faq')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('faq')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

    
    
      return parent::_prepareColumns();
  }
  protected function _filterStoreCondition($collection, $column){
    if (!$value = $column->getFilter()->getValue()) {
        return;
    }
    $this->getCollection()->addStoreFilter($value);
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('question_id');
        $this->getMassactionBlock()->setFormFieldName('faq');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('faq')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('faq')->__('Are you sure?')
        ));
        $statuses = Mage::getSingleton('faq/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('faq')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('faq')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
