<?php

class Webguys_Easytemplate_Block_Adminhtml_Edit_Template
    extends Mage_Adminhtml_Block_Widget
{

    protected $_templateBlocks = array();


    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('easytemplate/edit/template.phtml');
    }

    public function getTemplateWrapperHtml()
    {
        return $this->getLayout()->createBlock('core/template')->setTemplate('easytemplate/edit/box.phtml')->toHtml();
    }

    /**
     * Retrieve html templates for different types of product custom options
     *
     * @return string
     */
    public function getEmptyTemplates()
    {
        $templates = array();

        /** @var $configModel Webguys_Easytemplate_Model_Input_Parser */
        $configModel = Mage::getSingleton('easytemplate/input_parser');

        foreach ($configModel->getTemplates() as $template) {

            /** @var $box Webguys_Easytemplate_Block_Adminhtml_Edit_Box */
            $box = $this->getLayout()->createBlock( 'easytemplate/adminhtml_edit_box');
            $box->setTemplateModel($template);

            $templates[ $template->getCode() ] = $box->toHtml();

        }

        return $templates;
    }

    public function getExistingTemplatesHtml()
    {
        $group = $this->getGroup();
        $html = '';

        foreach( $group->getTemplateCollection() AS $template )
        {

            /** @var $box Webguys_Easytemplate_Block_Adminhtml_Edit_Box */
            $box = $this->getLayout()->createBlock( 'easytemplate/adminhtml_edit_box');
            $box->setTemplateModel($template);

            $html .= $box->toHtml();
        }

        return $html;

    }

    /**
     * @return Webguys_Easytemplate_Model_Group
     */
    public function getGroup()
    {
        if ($page = Mage::registry('cms_page')) {
            return Mage::helper('easytemplate')->getGroupByPageId( $page->getId() );
        }
        return Mage::getModel('easytemplate/group');
    }

    protected function _prepareLayout()
    {
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('easytemplate')->__('Add New Template'),
                    'class' => 'add',
                    'id'    => 'add_new_template'
                ))
        );

        return parent::_prepareLayout();
    }

    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    public function isInTemplateMode()
    {
        if ($page = Mage::registry('cms_page')) {
            return $page->getViewMode() == Webguys_Easytemplate_Model_Config_Source_Cms_Page_Viewmode::VIEWMODE_EASYTPL;
        }
        return false;
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('easytemplate')->__('Templates');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('easytemplate')->__('Templates');
    }

    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return false
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/page/' . $action);
    }
}