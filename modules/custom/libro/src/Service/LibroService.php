<?php

namespace Drupal\libro\Service;

use Drupal\Core\Database\Database;

class LibroService {
  public function librosLista() {
    $connection = Database::getConnection();
    $query = $connection->select('libros', 'n')
      ->fields('n', ['titulo', 'autor', 'APublicacion', 'sinopsis'])
      ->execute();

    foreach ($query as $guardar) {
      $filas[] = [
        $guardar->titulo,
        $guardar->autor,
        $guardar->APublicacion,
        $guardar->sinopsis,
      ];
    }

    return $filas;
  }

  public function datosLibro($id){
    $connection = Database::getConnection();
    $result = $connection->select('libros', 't')
      ->fields('t')
      ->condition('id', $id)
      ->execute()
      ->fetchAssoc();
      
    return $result;
  }

  public function generosLibro($id){
    $connection = Database::getConnection();
    $query = $connection->select('libro_genero', 't')
      ->fields('t')
      ->condition('libroId', $id)
      ->execute();
    
    $salida = [];
    foreach ($query as $genero){
      $salida[] = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($genero->taxoId)->getName();
    }
      
    return $salida;
  }

  Public function comparteGenero($id){
    $connection = Database::getConnection();
    $pertenceGeneros = $connection->select('libro_genero', 't')
      ->fields('t', ['taxoId'])
      ->condition('libroId', $id)
      ->execute();
    
    // Obtener las id de las taxos a las cuales petenece 
    $PerteneceGeneroId=[];
    foreach ($pertenceGeneros as $guardar) {
      $PerteneceGeneroId[] = [$guardar->taxoId];
    }

    // Obtenter los nombres de los Libros que comparte taxodermia
    $comparteTaxo=[];
    foreach ($PerteneceGeneroId as $taxoId) {
      $query = $connection->select('libro_genero', 'tg');
      $query->join('libros', 't', 'tg.libroId = t.id');
      $query->fields('t', ['titulo']);
      $query->condition('tg.taxoId', $taxoId);
      $query->condition('tg.libroId', $id, '!=');

      $result = $query->execute()->fetchAll();

      foreach ($result as $row) {
        if (!in_array($row->titulo, $comparteTaxo)) {
          $comparteTaxo[] = $row->titulo;
        }
      }
    }

    return $comparteTaxo;
  }

  public function saludoAleatorio(){
    $saludo = \Drupal::service('libro.saludo_manager');

    $informal = $saludo->createInstance('saludo_informal');
    $formal = $saludo->createInstance('saludo_formal');
    $antiguo = $saludo->createInstance('saludo_antiguo');

    $array =  array($informal, $formal, $antiguo);

    $eleccion = array_rand($array,1);

    $salida = $array[$eleccion]->getSaludo();

    return $salida;
  }
}