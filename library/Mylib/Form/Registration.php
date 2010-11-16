<?php
/**
 * 
 * Multipage form to capture a pledge record
 * @author sreejith <sreejith.chozhiyath@gmail.com>
 *
 */
class Mylib_Form_Registration extends Zend_Form{
	
	public function init(){

		$titles = array(
					'Miss' => 'Miss',
					'Mrs' => 'Mrs',
					'Mr' => 'Mr'
		        );
                            
		$genders = array(
					'MALE'=>'Male',
					'FEMALE'=>'Female',
					'NOMENTION'=>'Dont Ask'
				);
		
		//Sub form to capture basic customer info
		$customerBasic = new Zend_Form_SubForm();		
        $customerBasic->addElements(array(
	            new Zend_Form_Element_Select('title', array(
	                'required'   => true,
	                'label'      => 'Title:',
					'multiOptions' => $titles,
	                'validators' => array(
	                    'NotEmpty',
	            		array('InArray',
	            			false,
	            			array(array_keys($titles)))
	                )
	            )),
	            new Zend_Form_Element_Radio('gender', array(
	                'required'   => true,
	                'label'      => 'Gender:',
					'multiOptions' => $genders,
	                'validators' => array(
	                    'NotEmpty',
	            		array('InArray',
	            			false,
	            			array(array_keys($genders)))
	                )
	            )),
	            new Zend_Form_Element_Text('firstname', array(
	                'required'   => true,
	                'label'      => 'First Name:',
	                'validators' => array(
	                    'NotEmpty',
	            		array('Regex',
                          false,
                          array('/^[a-z0-9 \-]{2,}$/i'))
	                )
	            )),
	            new Zend_Form_Element_Text('lastname', array(
	                'required'   => true,
	                'label'      => 'Family Name:',
	                'validators' => array(
	                    'NotEmpty',
	            		array('Regex',
                          false,
                          array('/^[a-z0-9 \-]{2,}$/i'))
	                )
	            )),
	            new Zend_Form_Element_Text('email', array(
	                'required'   => true,
	                'label'      => 'Email:',
	                'validators' => array(
	                    'EmailAddress'
	                )
	            )),
	            new Zend_Form_Element_Text('dob', array(
	                'required'   => true,
	                'label'      => 'Date of Birth:',
	                'validators' => array(
	                    'Date'
	                )
	            ))
        ));
        
        //Sub form that captures phone numbers
        
        $contactNumPrefs = array(
        					'WORK' => 'Work Phone',
        					'HOME' => 'Home Phone',
        					'MOBILE' => 'Mobile Phone'
        				);
        
		$customerPhone = new Zend_Form_SubForm();		
        $customerPhone->addElements(array(
	            new Zend_Form_Element_Text('workphone', array(
	                'required'   => true,
	                'label'      => 'Work Phone Number:',
	                'validators' => array(
	            		array('Regex',
                          false,
                          array('/^[0-9 \-]{2,}$/'))
	                )
	            )),
	            new Zend_Form_Element_Text('homephone', array(
	                'required'   => true,
	                'label'      => 'Home Phone Number:',
	                'validators' => array(
	                    'NotEmpty',
	            		array('Regex',
                          false,
                          array('/^[0-9 \-]{2,}$/'))
	                )
	            )),
	            new Zend_Form_Element_Text('mobilephone', array(
	                'required'   => true,
	                'label'      => 'Mobile Phone Number:',
	                'validators' => array(
	                    'NotEmpty',
	            		array('Regex',
                          false,
                          array('/^[0-9 \-]{2,}$/'))
	                )
	            )),
	            new Zend_Form_Element_Radio('contactpreference', array(
	                'required'   => true,
	                'label'      => 'Preferred Contact Number:',
					'multiOptions' => $contactNumPrefs,
	                'validators' => array(
	                    'NotEmpty',
	            		array('InArray',
	            			false,
	            			array(array_keys($contactNumPrefs)))
	                )
	            ))
        ));
        
        // Attach sub forms to main form
        $this->addSubForms(array(
            'customerbasic' => $customerBasic,
        	'customerphone' => $customerPhone
        ));
	}
	
    /**
     * Prepare a sub form for display
     *
     * @param  string|Zend_Form_SubForm $spec
     * @return Zend_Form_SubForm
     */
    public function prepareSubForm($spec){
        if (is_string($spec)) {
            $subForm = $this->{$spec};
        } elseif ($spec instanceof Zend_Form_SubForm) {
            $subForm = $spec;
        } else {
            throw new Exception('Invalid argument passed to ' . __FUNCTION__ . '()');
        }
        
        $this->setSubFormDecorators($subForm)
             ->addSubmitButton($subForm)
             ->addSubFormActions($subForm);
             
        return $subForm;
    }

    /**
     * Add form decorators to an individual sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return Mylib_Form_Pledge
     */
    public function setSubFormDecorators(Zend_Form_SubForm $subForm){
        $subForm->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl',
                                   'class' => 'zend_form')),
            'Form',
        ));
        return $this;
    }
 
    /**
     * Add a submit button to an individual sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return Mylib_Form_Pledge
     */
    public function addSubmitButton(Zend_Form_SubForm $subForm){
        $subForm->addElement(new Zend_Form_Element_Submit(
            'save',
            array(
                'label'    => 'Save and continue',
                'required' => false,
                'ignore'   => true,
            )
        ));
        return $this;
    }
 
    /**
     * Add action and method to sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return Mylib_Form_Pledge
     */
    public function addSubFormActions(Zend_Form_SubForm $subForm){
        $subForm->setAction('/process')
                ->setMethod('post');
        return $this;
    }
	
}
