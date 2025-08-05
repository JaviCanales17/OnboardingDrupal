<?php

namespace Drupal\libro\Plugin\Saludo;

use Drupal\libro\Plugin\Saludo\SaludoPluginBase;

/**
 * Plugin de saludo informal.
 *
 * @Saludo(
 *   id = "saludo_informal",
 *   label = @Translation("Saludo informal"),
 *   description = @Translation("Saludo con tono informal")
 * )
 */
class SaludoInformal extends SaludoPluginBase {
  public function getSaludo(): string {
    return "Qué tal";
  }
}