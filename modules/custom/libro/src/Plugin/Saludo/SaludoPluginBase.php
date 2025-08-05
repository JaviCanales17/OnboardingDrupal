<?php

namespace Drupal\libro\Plugin\Saludo;

use Drupal\Component\Plugin\PluginBase;

abstract class SaludoPluginBase extends PluginBase {
  abstract public function getSaludo(): string;
}

