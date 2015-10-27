<?php

/**
 * Class Webguys_Easytemplate_Model_Input_Renderer_Validator_Link
 *
 */
class Webguys_Easytemplate_Model_Input_Renderer_Validator_Link extends Webguys_Easytemplate_Model_Input_Renderer_Validator_Base
{

    public function prepareForFrontend($data)
    {
        if (empty($data)) {
            return $data;
        }

        list($code, $id) = explode('/', $data);
        if ($code == 'product') {
            // TODO: Wirklich so laden?

            $product = Mage::getModel('catalog/product');
            $product->load($id);
            return $product->getProductUrl();
        } else {
            if ($code == 'category') {
                // TODO: Wirklich so laden?

                /** @var $product Mage_Catalog_Model_Category */
                $category = Mage::getModel('catalog/category');
                $category->load($id);
                return $category->getUrl();
            } else {
                if (is_numeric($data)) {
                    return Mage::Helper('cms/page')->getPageUrl($data);
                }
            }
        }

        if (substr($data, 0, 4) == 'http' || substr($data, 0, 1) == '.' || substr($data, 0, 1) == '/') {
            // do not touch http or relative links
            return $data;
        }

        return Mage::getUrl($data);
    }


}
