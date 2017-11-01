<?php
/**
 * @file
 * Contains \Drupal\natura_local_sform\Form\AutocompleteSForm.
 */

namespace Drupal\natura_local_sform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

/**
 * Форма с примерами автодополнения.
 */
class AutocompleteSForm extends FormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'autocomlpete_sform';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form= array(
      '#action' => 'search-destins',
      '#method' => 'get',
      '#attributes' => array('class' => array('form--inline')),
    );
    $form['keys'] = array(
      '#title' => $this->t('ON?'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#selection_settings' => array(
        'target_bundles' => array('town'),
      ),
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Search'),
      '#button_type' => 'primary',
    );
    $form['#attached']['library'][] = 'natura_local_sform/nl-sform';

    return $form;
  }
  /**
   * {@inheritdoc}
   */
//  public function validateForm(array &$form, FormStateInterface $form_state) {
//    $field = 'keys';
//    debug(array('validate', $form_state->getValue($field)));
//    if (strlen($form_state->getValue($field)) < 2 ) {
//      $form_state->setErrorByName($field, t('Please, 2 symbols or more'));
//    }
//    //parent::validateForm($form, $form_state);
//  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $path = '/test-test';
    $field = 'keys';
    $url = Url::fromUserInput($path, array('query' => array($field => $form_state->getValue($field))))->toString();
    $responce = new RedirectResponse($url);
    return $responce->send();
  }

}