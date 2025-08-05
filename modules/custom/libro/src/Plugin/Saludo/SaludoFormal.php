<?php

namespace Drupal\libro\Plugin\Saludo;

use Drupal\libro\Plugin\Saludo\SaludoPluginBase;

/**
 * Plugin de saludo formal.
 *
 * @Saludo(
 *   id = "saludo_formal",
 *   label = @Translation("Saludo formal"),
 *   description = @Translation("Saludo con tono formal")
 * )
 */
class SaludoFormal extends SaludoPluginBase {
  public function getSaludo(): string {
    return "Buenos días";
  }
}