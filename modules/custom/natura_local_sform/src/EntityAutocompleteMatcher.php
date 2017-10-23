<?php

namespace Drupal\natura_local_sform;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Tags;
use Drupal\Core\Url;

class EntityAutocompleteMatcher extends \Drupal\Core\Entity\EntityAutocompleteMatcher {

  /**
   * Gets matched labels based on a given search string.
   */
  public function getMatches($target_type, $selection_handler, $selection_settings, $string = '') {

    $matches = [];

    $options = [
      'target_type'      => $target_type,
      'handler'          => $selection_handler,
      'handler_settings' => $selection_settings,
    ];

    $handler = $this->selectionManager->getInstance($options);

    if (isset($string)) {
      // Get an array of matching entities.
      $match_operator = !empty($selection_settings['match_operator']) ? $selection_settings['match_operator'] : 'CONTAINS';
      $entity_labels = $handler->getReferenceableEntities($string, $match_operator, 10);


      // Loop through the entities and convert them into autocomplete output.
      foreach ($entity_labels as $values) {
        foreach ($values as $entity_id => $label) {

          $entity = \Drupal::entityTypeManager()->getStorage($target_type)->load($entity_id);
          $entity = \Drupal::entityManager()->getTranslationFromContext($entity);

          $type = !empty($entity->type->entity) ? $entity->type->entity->label() : $entity->bundle();
          $status = '';
          if($type == 'Place'){
            $fields = array(
              'route' => 'field_routes',
              'visit' => 'field_visits',
              'service' => 'field_services',
              'experience' => 'field_experiences',
            );
            $nds = natura_local_sform_get_refs($entity, $fields);
            $wideLabel = !empty($wasFirst) ? '' : '<span class="note">' . t('Recommended destinations...') . '</span></br>';
            $wasFirst = 1;
            $wideLabel .= '<span class="title">' . $label . '</span></br>';
            if(count($nds)) {
              foreach ($nds as $name => $count) {
                $wideLabel .= '<span class="item">' . \Drupal::translation()
                    ->formatPlural($count, '@count :title', '@count :titles', array(':title' => $name)) . '</span> | ';
              }
              $wideLabel = substr($wideLabel, 0, -3);
            }
            $url = Url::fromRoute('entity.node.canonical', array('node' => $entity_id), array('absolute' => TRUE));
            $key = $label;
            $label = $wideLabel . ' <span class="invisible-span">' . $url->toString() . '</span>';
            $key = preg_replace('/\s\s+/', ' ', str_replace("\n", '', trim(Html::decodeEntities(strip_tags($key)))));
            $key = Tags::encode($key);
            $matches[] = ['value' => $key, 'label' => $label];
          }
          else {
            //debug($entity->getEntityType()->id());
            if ($entity->getEntityType()->id() == 'node') {
              $status = ($entity->isPublished()) ? ", Published" : ", Unpublished";
            }

            $key = $label . ' (' . $entity_id . ')';
            // Strip things like starting/trailing white spaces, line breaks and tags.
            $key = preg_replace('/\s\s+/', ' ', str_replace("\n", '', trim(Html::decodeEntities(strip_tags($key)))));
            // Names containing commas or quotes must be wrapped in quotes.
            $key = Tags::encode($key);
            $label = $label . ' (' . $entity_id . ') '.$type;
            $matches[] = ['value' => $key, 'label' => $label];
          }
        }
      }
    }

    return $matches;
  }

}
