<?php

namespace Drupal\libro\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\libro\Service\LibroService;

class LibroContoller extends ControllerBase {
  protected $miServicio;

  public function __construct(LibroService $miServicio) {
    $this->miServicio = $miServicio;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('libro.libro_servicio')
    );
  }

  public function listadoLibros() {
    
    $datos = $this->miServicio->librosLista();
    $salida = [
      '#theme' => 'table',
      '#header' => ['Título', 'Autor', 'Año de Publicacion', 'Sinopsis'],
      '#rows' => $datos,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
    
    
    
    return $salida;
  }

  public function libroInfo($id) {
  
    $libro=$this->miServicio->datosLibro($id);
    $generos=$this->miServicio->generosLibro($id);
    if($libro){
      $salida=[
        '#theme' => 'libro',
        '#titulo' => $libro['titulo'],
        '#autor' => $libro['autor'],
        '#anio' => $libro['APublicacion'],
        '#sinopsis' => $libro['sinopsis'],
        '#generos'=> $generos,
        '#es_reciente' => ((int) $libro['APublicacion']) > 2015,
        '#attached' => [
          'library' => ['libro/estilos'],
        ],
        '#cache' => [
          'max-age' => 0,
        ],
      ];
    } else{
      $salida=[ '#markup' =>
        '<h1>Libro no encontrado</h1>'
      ];
    }

    return $salida;
  }

}