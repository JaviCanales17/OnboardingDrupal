<?php

namespace Drupal\registro_usuario\Service;

use Drupal\Core\Database\Database;

class RegistroServicio {
  public function datos() {
    $connection = Database::getConnection();
    $return = "";
    $result = $connection->select('registro_usuario_datos', 't')
        ->fields('t', ['nombre', 'correo'])
        ->execute();
    foreach ($result as $row) {
      $return .= '<tr> <td>' . $row->nombre . '</td> <td>' . $row->correo . '</td></tr>';
    }
    return $return;
  }

  public function datosUsuario($id){
    $connection = Database::getConnection();
    $result = $connection->select('registro_usuario_datos', 't')
      ->fields('t')
      ->condition('id', "$id")
      ->execute()
      ->fetchAssoc();
    return $result;
  }
}