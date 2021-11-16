<?php

namespace Drupal\custom_registration\services;
use Drupal\Core\Database\Driver\mysql\Connection;

/**
 * Class DbOperation.
 */
class DbOperation {

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $database;

  /**
   * Constructs a new DbOperation object.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /*   Set Data Function */
  public function setData($form_state)
  {
     $this->database->insert('custom_registration')
     ->fields(array(
           'username'=> $form_state->getValue('username'),
           'first_name'=> $form_state->getValue('first_name'),
           'last_name'=> $form_state->getValue('last_name'),
           'gender'=> $form_state->getValue('gender'),
           'country'=> $form_state->getValue('country'),
           'state'=> $form_state->getValue('state'),
           'city'=> $form_state->getValue('city'),
           'dob'=> $form_state->getValue('dob'),
           'created'=> time(),
       ))->execute();
   }

   /*   Get Data Function */

  public function getData_all()
  {
     $qry = $this->database->select('custom_registration','creg');
     $qry->fields('creg');
     $data = $qry->execute()->fetchAll();
     return $data;
   }

   public function getData_id($uid)
  {
     $qry = $this->database->select('custom_registration','creg');
     $qry->condition('creg.id', $uid, '=');
     $qry->fields('creg');
     $data = $qry->execute()->fetchAll();
     return $data;
   }

     public function getData_username($username)
  {
     $qry = $this->database->select('custom_registration','creg');
     $qry->condition('creg.username', $username, '=');
     $qry->fields('creg');
     $data = $qry->execute()->fetchAll();
     return $data;
   }

      public function getData_username_id($username,$uid)
  {
     $qry = $this->database->select('custom_registration','creg');
     $qry->condition('creg.username', $username, '=');
     $qry->condition('creg.id', $uid, '<>');
     $qry->fields('creg');
     $data = $qry->execute()->fetchAll();
     return $data;
   }

     public function deleteData_id($uid)
  {
     $qry = $this->database->delete('custom_registration');
     $qry->condition('id', $uid, '=');    
     $qry->execute();   
   }


   public function updateData_id($form_state,$uid)
   {     
          $this->database->update('custom_registration')
     ->fields(array(
           'username'=> $form_state->getValue('username'),
           'first_name'=> $form_state->getValue('first_name'),
           'last_name'=> $form_state->getValue('last_name'),
           'gender'=> $form_state->getValue('gender'),
           'country'=> $form_state->getValue('country'),
           'state'=> $form_state->getValue('state'),
           'city'=> $form_state->getValue('city'),
           'dob'=> $form_state->getValue('dob'),       

       ))->condition('id', $uid)->execute();


   }

}
