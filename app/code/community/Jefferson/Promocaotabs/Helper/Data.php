<?php

/**
 * 
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * @category    Jefferson
 * @package     Jefferson_Promocaotabs
 * @author		Jefferson Batista Porto <jefferson.b.porto@gmail.com>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 */
 
	class Jefferson_Promocaotabs_Helper_Data  extends Mage_Core_Helper_Abstract {
		
		public static function calcPerc($val1, $val2){
			$total = ($val1 - $val2);
			$total = $total / $val1;
			$total = $total * 100;
			
			return "-".number_format($total,'0')."%";
		}
		
		public static function formatCategory(){
			$cat = Mage::getStoreConfig('catalog/jefferson_promocaotabs/category_list');
			return explode(',', $cat);
		}
		
		
		
	}
?>