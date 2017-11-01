<?php

namespace Drupal\natura_local_search\Ajax;

use Drupal\Core\Ajax\CommandInterface;

class jsonViewCommand implements CommandInterface {

  protected $tab;

  protected $view;

  public function __construct($view, $tab) {
    $this->view = $view;
    $this->tab = $tab;
  }

  public function render() {
    return array(
      'command' => 'replaceWith',
      'selector' => $this->tab,
      'data' => $this->view,
    );
  }
}
