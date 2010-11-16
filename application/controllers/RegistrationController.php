<?php
/**
 * The pledge multi page data capture controller
 */
class RegistrationController extends Zend_Controller_Action{
	
    protected $_form;
    protected $_namespace = 'RegistationController';
    protected $_session;
    
    public function init(){
    	//will knock off later.
    	$this->_helper->layout->setLayout('simple');
    }
    
    /**
     * 
     * return the form that we use to capture pledge record
     */
    public function getForm(){
        if (null === $this->_form) {
            $this->_form = new Mylib_Form_Registration();
        }
        
        return $this->_form;
    }
    
    /**
     * Get the session namespace we're using
     *
     * @return Zend_Session_Namespace
     */
    public function getSessionNamespace(){
        if (null === $this->_session) {
            $this->_session = new Zend_Session_Namespace($this->_namespace);
        }
 
        return $this->_session;
    }
    
    /**
     * Get a list of forms already stored in the session
     *
     * @return array
     */
    public function getStoredForms(){
        $stored = array();
        foreach ($this->getSessionNamespace() as $key => $value) {
            $stored[] = $key;
        }
        
        return $stored;
    }
    
	/**
     * Get list of all subforms available
     *
     * @return array
     */
    public function getPotentialForms(){
        return array_keys($this->getForm()->getSubForms());
    }
    
    /**
     * What sub form was submitted?
     *
     * @return false|Zend_Form_SubForm
     */
    public function getCurrentSubForm(){
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return false;
        }
 
        foreach ($this->getPotentialForms() as $name) {
            if ($data = $request->getPost($name, false)) {
                if (is_array($data)) {
                    return $this->getForm()->getSubForm($name);
                    break;
                }
            }
        }
 
        return false;
    }
    
    /**
     * Get the next sub form to display
     *
     * @return Zend_Form_SubForm|false
     */
    public function getNextSubForm(){
        $storedForms    = $this->getStoredForms();
        $potentialForms = $this->getPotentialForms();

        foreach ($potentialForms as $name) {
            if (!in_array($name, $storedForms)) {
                return $this->getForm()->getSubForm($name);
            }
        }
 
        return false;
    }
    
    /**
     * Is the sub form valid?
     *
     * @param  Zend_Form_SubForm $subForm
     * @param  array $data
     * @return bool
     */
    public function subFormIsValid(Zend_Form_SubForm $subForm, array $data){
        $name = $subForm->getName();
        if ($subForm->isValid($data)) {
            $this->getSessionNamespace()->$name = $subForm->getValues();
            return true;
        }
 
        return false;
    }
    
    /**
     * Is the full form valid?
     *
     * @return bool
     */
    public function formIsValid()
    {
        $data = array();
        foreach ($this->getSessionNamespace() as $key => $info) {
            $data[$key] = $info[$key];
        }

        return $this->getForm()->isValid($data);
    }

	
    /*****************************************/
    /*************** ACTIONS *****************/
    /*****************************************/
    
    public function indexAction(){
        // Either re-display the current page, or grab the "next"
        // (first) sub form
        if (!$form = $this->getCurrentSubForm()) {
            $form = $this->getNextSubForm();
        }
        
        $this->view->form = $this->getForm()->prepareSubForm($form);
    }

    public function processAction(){

        if (!$form = $this->getCurrentSubForm()) {
            return $this->_forward('index');
        }

		if (!$this->subFormIsValid($form, $this->getRequest()->getPost())) {
            $this->view->form = $this->getForm()->prepareSubForm($form);
            return $this->render('index');
        }

        if (!$this->formIsValid()) {
            $form = $this->getNextSubForm();
            $this->view->form = $this->getForm()->prepareSubForm($form);
            return $this->render('index');
        }

        // Valid form!
        // Render information in a verification page
        $this->view->info = $this->getSessionNamespace();
        $this->render('showcapture');
    }
    
    public function showcaptureAction(){
    	
    } 
	
}
