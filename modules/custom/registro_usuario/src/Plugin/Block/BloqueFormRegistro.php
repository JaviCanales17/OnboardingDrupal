<?php

namespace Drupal\registro_usuario\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Define un bloque con un formulario.
 *
 * @Block(
 *   id = "registro_usuario_formulario",
 *   admin_label = @Translation("Formulario de registro de usuario"),
 * )
 */
class BloqueFormRegistro extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\registro_usuario\Form\RegistroFormulario');
  }
}