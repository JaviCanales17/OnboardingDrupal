<?php

namespace Drupal\libro\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 *
 * @Block(
 *   id = "chart_block",
 *   admin_label = @Translation("Bloque que contiene un grafo de chart"),
 * )
 */
class ChartBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $salida = [];

    $salida['#attached']['library'][] = 'libro/chartjs';

    $salida['chart'] = [
      '#markup' => Markup::create('
        <canvas id="myChart" width="400" height="200"></canvas>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
          const ctx = document.getElementById("myChart");
          new Chart(ctx, {
            type: "bar",
            data: {
              labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
              datasets: [{
                label: "# of Votes",
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 1
              }]
            },
            options: {
              responsive: true,
              scales: {
                y: { beginAtZero: true }
              }
            }
          });
        });
        </script>
      '),
    ];

    return $salida;
  }
}