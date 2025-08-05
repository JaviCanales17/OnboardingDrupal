<?php

namespace Drupal\libro\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Define un bloque con un formulario.
 *
 * @Block(
 *   id = "saludo_block",
 *   admin_label = @Translation("Bloque que saludara de una forma aleatoria"),
 * )
 */
class SaludoBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $mi_servicio = \Drupal::service('libro.libro_servicio');
    $mensaje = $mi_servicio->saludoAleatorio();

    $salida=[ '#markup' =>
        '<h1>'.$mensaje.'</h1>'
      ];

    return $salida;
  }
}