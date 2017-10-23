<?php
/**
 * @file
 * Contains \Drupal\natura_local_sform\Plugin\Block\NLSearchBlock.
 */

namespace Drupal\natura_local_sform\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * Provides Custom Block.
 *
 * @Block(
 * id = "nl_search_block",
 * admin_label = @Translation("NL Search Block"),
 * category = @Translation("Natura Local")
 * )
 */

class NLSearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = array();

    //$build['#markup'] = '' . t('Search Destination') . '';
    $build['form'] = \Drupal::formBuilder()->getForm('Drupal\natura_local_sform\Form\AutocompleteSForm');

    return $build;
  }

}