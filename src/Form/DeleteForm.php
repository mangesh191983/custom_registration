<?php

namespace Drupal\custom_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;

/**
 * Class RegistrationForm.
 */
class DeleteForm extends ConfirmFormBase {

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
    return 'delete_form';
  }



  public function getQuestion() { 
    return t('Do you want to delete %cid?', array('%cid' => $this->user));
  }
 public function getCancelUrl() {
    return new Url('custom_registration.datalist');
}
public function getDescription() {
    return t('Only do this if you are sure!');
  }
  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete it!');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
   public function buildForm(array $form, FormStateInterface $form_state, $user = NULL) {
         if($user && $user!='')   $this->user = $user;
   
    return parent::buildForm($form, $form_state);
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      
          $query = $this->dataService->deleteData_id($this->user);
          drupal_set_message("succesfully deleted");
          $form_state->setRedirect('custom_registration.datalist');
  }
}
