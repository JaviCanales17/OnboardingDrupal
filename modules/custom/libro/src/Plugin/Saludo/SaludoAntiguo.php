<?php

namespace Drupal\libro\Plugin\Saludo;

use Drupal\libro\Plugin\Saludo\SaludoPluginBase;

/**
 * Plugin de saludo antiguo.
 *
 * @Saludo(
 *   id = "saludo_antiguo",
 *   label = @Translation("Saludo antiguo"),
 *   description = @Translation("Saludo con tono antiguo")
 * )
 */
class SaludoAntiguo extends SaludoPluginBase {
  public function getSaludo(): string {
    return "Que pasa tron";
  }
}