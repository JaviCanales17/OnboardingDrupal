<?php

namespace Drupal\registro_usuario\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;


/**
 * Define un bloque con un formulario.
 *
 * @Block(
 *   id = "registro_usuario_ultimos_usuarios",
 *   admin_label = @Translation("Los ultimos 5 usuarios creados"),
 * )
 */
class BloqueUltimosUsuarios extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
        $connection = Database::getConnection();
        $query = $connection->select('registro_usuario_datos', 'n')
            ->fields('n', ['id','nombre', 'creado'])
            ->orderBy('id', 'DESC')
            ->range(0, 5)
            ->execute();

        foreach ($query as $guardar) {
            $fila[] = [
                $guardar->nombre,
                $guardar->creado,
            ];
        }

        $salida = [
            '#theme' => 'table',
            '#header' => ['Nombre', 'Fecha de creacion'],
            '#rows' => $fila,
            '#cache' => [
                'max-age' => 0,
            ],
        ];

        return $salida;
    }
}