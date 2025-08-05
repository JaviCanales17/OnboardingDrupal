<?php

namespace Drupal\registro_usuario\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\registro_usuario\Service\RegistroServicio;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegistroUsuarioControlador extends ControllerBase {
  protected $miServicio;

  public function __construct(RegistroServicio $miServicio) {
    $this->miServicio = $miServicio;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('registro_usuario.mi_servicio')
    );
  }

  public function mostrarMensaje() {
    return [
      '#markup' => '
      <h2>Usuarios registrados</h2>
      <table>
        <tr>
          <th>Nombres</th>
          <th>Correos</th>
        </tr>' . 
        $this->miServicio->datos().
      '</table>',
    ];
  }

  public function apiUsuarios($id) {
    $usuario=$this->miServicio->datosUsuario($id);

    if (!$usuario) {
      $salida = new JsonResponse(['error' => 'Usuario no encontrado'], 404);
    }else{
      $salida = new JsonResponse($usuario);
    }

    return $salida;
  }
}