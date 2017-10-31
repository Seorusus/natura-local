<?php

namespace Drupal\natura_local_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

class NLSearchPageController extends ControllerBase {

  public function jsonData(Request $request) {
    $output = array();
    $output[] = array('map' => views_embed_view('piois_etc', 'default', '59+60'));
    $output[] = array('result'=> "OK");
    return new JsonResponse($output);
  }

  public function searchPage(Request $request) {
    $keys = $request->query->get('keys');
    $view = \Drupal\views\Views::getView('search_destins');
    $view->setDisplay('default');
    $view->execute();
    $result = $view->result;
    $places = array();
    $fieldsByType = array(
      'town' =>  \Drupal::service('entity_field.manager')->getFieldDefinitions('node', 'town'),
      'route' => \Drupal::service('entity_field.manager')->getFieldDefinitions('node', 'route'),
    );
    $fields = _natura_local_search_get_names();
    foreach ($result as $key => $value) {
      $entity = $value->_object->getValue();
      $type = $entity->get('type')->getValue()[0]['target_id'];
      if($type != 'town'){
        if($type == 'route'){
          _natura_local_search_fill_places($entity, $type, $fields, $fieldsByType, $places);
        }
        $query = \Drupal::entityQuery('node')
          ->condition('type', array('town','route'), 'IN')
          ->condition('status', 1)
          ->condition($fields[$type], $entity->get('nid')->getValue()[0]['value']);
        $nids = $query->execute();
        foreach ($nids as $id => $nid){
          if(!isset($places['town'][$nid]) && !isset($places['town'][$nid])){
            $entity = Node::load($nid);
            $type = $entity->get('type')->getValue()[0]['target_id'];
            _natura_local_search_fill_places($entity, $type, $fields, $fieldsByType, $places);
          }
        }
      }
      else{
        _natura_local_search_fill_places($entity, $type, $fields, $fieldsByType, $places);
      }
    }
    $arg = '';
    $first = FALSE;
    foreach($places as $place){
      foreach($place as $nid => $val){
        if($first){
          $arg .= '+'.$nid;
        }
        else{
          $arg = $nid;
          $first = TRUE;
        }
      }
    }
    $output =  array(
      '#theme' => 'natura_local_search',
      '#keys' => $this->t($keys),
      '#places' => $places,
      '#form' => \Drupal::formBuilder()->getForm('Drupal\natura_local_sform\Form\AutocompleteSForm'),
      '#map' => views_embed_view('piois_etc', 'default', $arg),
    );
    $output['#attached']['library'][] = 'natura_local_search/nl-search';
    $element['#markup'] = '<br>' . ' : ';
    return $output;
  }

}
