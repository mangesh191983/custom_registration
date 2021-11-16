<?php

namespace Drupal\custom_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class RegistrationForm.
 */
class EditRegistrationForm extends FormBase {

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
   protected $dataService;
   protected $user;


  
   public function __construct()
   {
     $this->dataService = \Drupal::service('custom_registration.dboperation');
   }
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,$user=NULL) {

    if($user && $user!=''){
      $this->user = $user;
    $data = $this->dataService->getData_id($this->user);

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#required' => TRUE,
      '#prefix' => '<div id="user-result"></div>',      
      '#ajax' => [
         'callback' => '::checkUserValidation',
         'effect' => 'fade',
         'event' => 'change',
         'method' => 'replaceWith',
          'progress' => [
             'type' => 'throbber',
             'message' => NULL,
          ], 
          ],
      '#default_value' =>  $data[0]->username,
    ];
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#required' => TRUE,
      '#default_value' =>  $data[0]->first_name,
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#required' => TRUE,
      '#default_value' =>  $data[0]->last_name,
    ];
    $form['gender'] = [
      '#type' => 'select',
      '#title' => $this->t('Gender'),
      '#options' => ['Male' => $this->t('Male'), 'Female' => $this->t('Female'), 'Other' => $this->t('Other')],
      '#size' => 1,
      '#weight' => '0',
      '#required' => TRUE,
      '#default_value' =>  $data[0]->gender,
    ];
    $form['dob'] = [
      '#type' => 'date',
      '#title' => $this->t('Date Of Birth'),
      '#weight' => '0',
      '#required' => TRUE,
      '#default_value' =>  $data[0]->dob,
    ];
    $form['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Country'),
      '#options' => ['India' => $this->t('India'), 'Sri Lanka' => $this->t('Sri Lanka'), 'Nepal' => $this->t('Nepal'), 'China' => $this->t('China'), 'Japan' => $this->t('Japan')],
      '#size' => 1,
      '#weight' => '0',
      '#required' => TRUE,
      '#default_value' =>  $data[0]->country,
    ];   
    $form['state'] = [
      '#type' => 'select',
      '#title' => $this->t('State'),
      '#options' => ['Gujart' => $this->t('Gujart'), 'Maharashtra' => $this->t('Maharashtra'), 'Goa' => $this->t('Goa'), 'Tamilnadu' => $this->t('Tamilnadu'), 'Karnataka' => $this->t('Karnataka')],
      '#size' =>1,
      '#weight' => '0',
      '#required' => TRUE,
      '#default_value' =>  $data[0]->state,
    ];
     $form['city'] = [
      '#type' => 'select',
      '#title' => $this->t('City'),
      '#options' => ['Nashik' => $this->t('Nashik'), 'Mumbai' => $this->t('Mumbai')],
      '#size' => 1,
      '#weight' => '0',
      '#required' => TRUE,
      '#default_value' =>  $data[0]->city,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.

    if (strlen($value) < 1) {
        $form_state->setErrorByName($key, $this->t('This field is required.'));
      }

    $data = $this->dataService->getData_username_id($form_state->getValue('username'),$this->user);

    if (count($data)>0){       
      $form_state->setErrorByName('username', $this->t('Username Already exists.'));
    }

    parent::validateForm($form, $form_state);
  }
}



  // public function checkUserValidation(array $form, FormStateInterface $form_state) {
  //  $ajax_response = new AjaxResponse();

  //  $data = $this->dataService->getData_username($form_state->getValue('username'));
  //  $ajax_response->addCommand(new HtmlCommand('#user-result', $data));


  // //  exit;
 
  // // // Check if User or email exists or not
  // //  if ($data > 0) {    
  // //    //$form_state->setErrorByName('username', $this->t('Username exists.'));
  // //     $ajax_response->addCommand(new HtmlCommand('#user-result', $data));
  // //  } 

  
  //  return $ajax_response;
  //  }


    public function checkUserValidation(array $form, FormStateInterface $form_state) {
   $ajax_response = new AjaxResponse();

   $data = $this->dataService->getData_username_id($form_state->getValue('username'),$this->user);

  // return new Response('<pre>' . var_export($data, TRUE) . '</pre>');

   if (count($data)>0){
    
 
   $ajax_response->addCommand(new HtmlCommand('#user-result','Username '.$form_state->getValue('username').' Already exists.')); 
    
    // $form_state->setValue('username', [['value' => '']]);
    //  $form_state->setErrorByName('username', $this->t('Username Already exists.'));
   }
   else {
     $ajax_response->addCommand(new HtmlCommand('#user-result',''));
   }

   return $ajax_response;
   }




  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
       $query = $this->dataService->updateData_id($form_state,$this->user);
        drupal_set_message("succesfully updated");
        $form_state->setRedirect('custom_registration.datalist');
  }

}
