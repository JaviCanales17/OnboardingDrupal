<?php

namespace Drupal\libro\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Define una anotación de tipo Saludo.
 *
 * @Annotation
 */
class Saludo extends Plugin {
  public $id;
  public $label;
  public $description;
}