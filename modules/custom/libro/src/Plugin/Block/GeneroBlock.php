<?php

namespace Drupal\libro\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;


/**
 * Define un bloque con un formulario.
 *
 * @Block(
 *   id = "genero_block",
 *   admin_label = @Translation("Libros relacionados por género"),
 * )
 */
class GeneroBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * {@inheritdoc}
   */
  
   // Creacion de la estructura para obtener el nombre de la ruta actual
  protected $routeMatch;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  public function build() {
    $mi_servicio = \Drupal::service('libro.libro_servicio');
    $rutaActual = $this->routeMatch->getRouteName();
    
    //comprueba si la ubicacion actual forma de libro.info_libro
    if ($rutaActual === "libro.info_libro" ){
      $id = $this->routeMatch->getParameter('id');
      $librosComparte = $mi_servicio->comparteGenero($id);
      $markup="";
      foreach ($librosComparte as $libro){
        $markup .= $libro.", ";
      }
    }else{
      $markup = '<h1> Solo esta disponible en la informacion del libro </h1>';
    }

    return [  
      '#markup' => $markup,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }
}