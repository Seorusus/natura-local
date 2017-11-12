<?php


namespace Drupal\natura_local_search\Ajax;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\SettingsCommand;
use Symfony\Component\HttpFoundation\Request;

class jsonViewController {
  public function Tab($tab, $view, $display, $arg) {
    $v = views_embed_view($view, $display, $arg);
    $vr = \Drupal::service('renderer')->render($v);
    $response = new AjaxResponse();
    $response->addCommand(new jsonViewCommand($vr, $tab));

// If this above line is replaced with ReplaceCommand (or any other built-in ajax command)
// and the view replaced with a simple render array the output is still unrendered json.

    return $response;
  }
  public function Events(Request $request){
    $response = new AjaxResponse();
    $setting['something']['foo'] = 'somesetting';
    $response->addCommand(new SettingsCommand($setting, TRUE));
    return $response;

    //$command = new SettingsCommand(array('something' => 'somesetting'), TRUE);

  }
}
