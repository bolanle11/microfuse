<?php 
class DCKAP_Faq_Block_Faq extends Mage_Core_Block_Template
{
    private $_itemPerPage;
    private $_pageFrame = 8;
    private $_curPage = 1;
    
    public function _construct()
    {
        parent::_construct();
      $this->_itemPerPage = Mage::getStoreConfig('faq/faq_group/faq_text');
      
    }
    public function getFaq($keyword = null)
    {   
        $currentStore = Mage::app()->getStore(true)->getId();
        $collection = Mage::getModel('faq/faq')->getCollection();

        $collection->addFieldToSelect('question_id')
        ->addFieldToSelect('question')
        ->addFieldToSelect('answer')
        ->addFieldToFilter('answer', array('neq' => '' ))
        ->addFieldToSelect('store_id')
        ->addFieldToFilter('status',array('eq'=>1))
        ->addFieldToFilter(
            array('store_id','store_set','store_id','store_set' ),
            array(
            array('eq'=>Mage::app()->getStore(true)->getId()), 
            array('like'=>'%,'.$currentStore.',%'),
            array('eq'=>'0'), 
            array('like'=>'%,0,%')
            )
        );
        if(!Mage::getStoreConfig('faq/faq_group/viewsorter')){
            $collection->setOrder('priority','ASC');   
        }else{
            $collection->setOrder('views','DESC');   
        }
        if($keyword){
            $collection->addFieldToFilter(
                array('question','answer' ),
                array(
                array('like'=>'%'.$keyword.'%'), 
                array('like'=>'%'.$keyword.'%')
                )
            );
        }
        if(Mage::getStoreConfig('faq/faq_group/category_enabled')){
        if($this->getRequest()->getParam('category')){
          $collection->addFieldtoFilter('category',$this->getRequest()->getParam('category'));
        }
        }
        return $collection;
    }
    
    public function getCategories()
    {   
        $currentStore= Mage::app()->getStore(true)->getId();
        $collection = Mage::getModel('faq/faq')->getCollection();

        $collection  = Mage::getModel('faq/category')->getCollection()->setOrder('priority','ASC'); 
        $collection->addFieldToFilter(
            array('store_id','store_set','store_id','store_set' ),
            array(
            array('eq'=>$currentStore), 
            array('like'=>'%,'.$currentStore.',%'),
            array('eq'=>'0'), 
            array('like'=>'%,0,%')
            )
        );
        $collection->addFieldToFilter('status',array('eq'=>1));
        return $collection;
    }
    public function getCurrentPage()
    {
        return $this->getRequest()->getParam('p');
    }

    public function getCollection($collection = 'null')
    {
       
        if($collection != 'null'){
            $page = $this->getRequest()->getParam('p');
            if($page) $this->_curPage = $page;
            
            $collection->setCurPage($this->_curPage);
            $collection->setPageSize($this->_itemPerPage);
            return $collection;
        }

    }
    
    public function getPagerHtml($collection = 'null')
    {    
        $html = false;
        if($collection == 'null') return;


        if($collection->count() > $this->_itemPerPage)
        {
            $curPage = $this->getRequest()->getParam('p');
            $pager = (int)($collection->count() / $this->_itemPerPage);
            $count = ($collection->count() % $this->_itemPerPage == 0) ? $pager : $pager + 1 ;
            $url = $this->getPagerUrl();
            $start = 1;
            $end = $this->_pageFrame;
            $category = $this->getRequest()->getParam('category');
            $keyword = $this->getRequest()->getParam('keyword');
            $slash = "?";
            if($category || $keyword){
                $slash = "&";
            }
            
            $html .= '<ol>';
            if(isset($curPage) && $curPage != 1){
                $start = $curPage - 1;                                        
                $end = $start + $this->_pageFrame;
            }else{
                $end = $start + $this->_pageFrame;
            }
            if($end > $count){
                $start = $count - ($this->_pageFrame-1);
            }else{
                $count = $end-1;
            }
            
            for($i = $start; $i<=$count; $i++)
            {
                if($i >= 1){
                    if($curPage){
                        $html .= ($curPage == $i) ? '<li class="current">'. $i .'</li>' : '<li><a href="'.$url.$slash.'p='.$i.'">'. $i .'</a></li>';
                    }else{
                        $html .= ($i == 1) ? '<li class="current">'. $i .'</li>' : '<li><a href="'.$url.$slash.'p='.$i.'">'. $i .'</a></li>';
                    }
                }
                
            }
            
            $html .= '</ol>';
        }
        
        return $html;
    }
    
    public function getPagerUrl()   
    {
        $cur_url = mage::helper('core/url')->getCurrentUrl();
        $category = $this->getRequest()->getParam('category');
        $keyword = $this->getRequest()->getParam('keyword');
        $new_url = preg_replace('/\?p=.*/', '', $cur_url);
        if($category){
            $new_url = preg_replace('/\&p=.*/', '', $cur_url);
        }
        if($keyword){
            $new_url = preg_replace('/\&p=.*/', '', $cur_url);
        }
        return $new_url;
    }

    
}
?>