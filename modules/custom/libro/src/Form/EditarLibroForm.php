<?php

namespace Drupal\libro\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Define un formulario que almacena datos en la base de datos.
 */
class EditarLibroForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'edicion_usuario_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id=null) {
    $connection = Database::getConnection();
    $libro = $connection->select('libros', 'm')
      ->fields('m', ['id', 'titulo', 'autor', 'APublicacion', 'sinopsis'])
      ->condition('id', $id)
      ->execute()
      ->fetchAssoc();

    // obtencion de los las id de taxonomia de las cuales esta relaciona el libro actual
    $generos = $connection->select('libro_genero', 't')
      ->fields('t', )
      ->condition('libroId', $id)
      ->execute();
    
    $opcionGeneros=[];
    foreach ($generos as $termino) {
      $opcionGeneros[] = $termino->taxoId;
    }

    // obtencion de los campos que apareceran en el formulario a travez de id y nombre
    $Terminos = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('generos');
    $opcionesTaxo = [];
    
    foreach ($Terminos as $termino) {
      $opcionesTaxo[$termino->tid] = $termino->name;
    }


    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $libro['id'],
    ];

    $form['titulo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Título'),
      '#value' => $libro['titulo'],
      '#required' => TRUE,
    ];

    $form['autor'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Autor'),
      '#value' => $libro['autor'],
      '#required' => TRUE,
    ];

    $form['APublicacion'] = [
      '#type' => 'number',
      '#title' => $this->t('Año de publicacion'),
      '#value' => $libro['APublicacion'],
      '#required' => TRUE,
    ];

    $form['sinopsis'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Sinopsis'),
      '#value' => $libro['sinopsis'],
      '#required' => TRUE,
    ];
    
    $form['generos'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Géneros'),
      '#default_value' => $opcionGeneros,
      '#options' => $opcionesTaxo,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Guardar'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getValue('id');
    $titulo = $form_state->getValue('titulo');
    $autor = $form_state->getValue('autor');
    $APublicacion = $form_state->getValue('APublicacion');
    $sinopsis = $form_state->getValue('sinopsis');
    $generos = array_filter($form_state->getValue('generos'));
    
    // Insertar en la base de datos.
    $connection = Database::getConnection();
    $connection->update('libros')
      ->fields([
        'titulo' => $titulo,
        'autor' => $autor,
        'APublicacion' => $APublicacion,
        'sinopsis' => $sinopsis,
      ])
      ->condition('id', $id)
      ->execute();
    
    // Editar géneros
    $connection->delete('libro_genero')
      ->condition('libroId', $id)
      ->execute();
    
    if ($generos){
      foreach ($generos as $taxoId) {
        $connection->insert('libro_genero')
          ->fields([
            'libroId' => $id,
            'taxoId' => $taxoId
          ])
          ->execute();
      }
    };

    $form_state->setRedirect('libro.mostrar_libros');
  }
}