<?php

class DCKAP_Faq_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('categoryGrid');
      $this->setDefaultSort('cat_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);

  }

  protected function _prepareCollection()
  {
    $collection = Mage::getModel('faq/category')->getCollection();
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('cat_id', array(
          'header'    => Mage::helper('faq')->__('Category ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'cat_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('faq')->__('Name'),
          'align'     =>'left',
          'index'     => 'name',
      ));
       $this->addColumn('description', array(
          'header'    => Mage::helper('faq')->__('Description'),
          'align'     =>'left',
          'index'     => 'description',
      ));

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

        $this->addColumn('priority',
            array(
                'header' => Mage::helper('faq')->__('Priority'),
                'width' => '50px',
                'index' => 'priority'
            )
        );
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
        $this->setMassactionIdField('cat_id');
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
