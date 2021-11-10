<?php
namespace Drupal\custom_registration\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Database;


use Symfony\Component\HttpFoundation\Response;

use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\custom_registration\Controller
 */
class Datalist extends ControllerBase {


   protected $dataService;

 
  
   public function __construct()
   {   
      $this->dataService = \Drupal::service('custom_registration.dboperation');
   }
  

  /**
   * Returns a render-able array for a test page.
   */
  public function getrecord() {
    //create table header
         $header = array(
         'id'=> t('SrNo'),
         'username' => t('User Name'),
         'first_name' => t('Fist Name '),
         'last_name' => t('Last Name'),
         'gender' => t('Gender'),
         'opt' => t('operations'),
         );


    $results = $this->dataService->getData_all();



       $rows=array();


    
    foreach($results as $data){
      
   
        $operate = '<a href="/custom_registration/form/edit_register_form/'.$data->id.'">Edit</a>|<a href="/custom_registration/delete_user/'.$data->id.'">delete</a>';
        //print the data from table
         $rows[$data->id] = array(
             'id' =>$data->id,
             'name' => $data->username,
             'firstname' => $data->first_name,
             'lastname' => $data->last_name,            
             'gender' => $data->gender,  
             'opt' => $this->t($operate),    
           
         );
        
    }
        //display data in site
         $form['table'] = [
         '#type' => 'table',
         '#header' => $header,
         '#rows' => $rows,
         '#empty' => t('No users found'),
     ];
      // Finally add the pager.
        $form['pager'] = array(
          '#type' => 'pager'
      );
     return $form;

    }
}



?>