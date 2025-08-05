<?php

namespace Drupal\libro\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Define un formulario que almacena datos en la base de datos.
 */
class AgregarLibroForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'Registro_bloque';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // obtencion de los campos que aparecerán en el formulario a través de id y nombre
    $Terminos = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('generos');
    $opcionesTaxo = [];
    foreach ($Terminos as $termino) {
      $opcionesTaxo[$termino->tid] = $termino->name;
    }

    $form['titulo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Título'),
      '#required' => TRUE,
    ];

    $form['autor'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Autor'),
      '#required' => TRUE,
    ];

    $form['APublicacion'] = [
      '#type' => 'number',
      '#title' => $this->t('Año de publicacion'),
      '#required' => TRUE,
    ];

    $form['sinopsis'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Sinopsis'),
      '#required' => TRUE,
    ];

    $form['generos'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Géneros'),
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
    $titulo = $form_state->getValue('titulo');
    $autor = $form_state->getValue('autor');
    $APublicacion = $form_state->getValue('APublicacion');
    $sinopsis = $form_state->getValue('sinopsis');
    $generos = array_filter($form_state->getValue('generos'));
    
    // Insertar en la base de datos.
    $connection = Database::getConnection();
    $connection->insert('libros')
      ->fields([
        'titulo' => $titulo,
        'autor' => $autor,
        'APublicacion' => $APublicacion,
        'sinopsis' => $sinopsis,
      ])
      ->execute();
    
    $libroId = $connection->query("SELECT MAX(id) FROM {libro}")->fetchField();

    // Insertar géneros
    foreach ($generos as $taxoId) {
      $connection->insert('libro_genero')
        ->fields([
          'libroId' => $libroId,
          'taxoId' => $taxoId
        ])
        ->execute();
    }

    \Drupal::messenger()->addMessage($this->t('El libro "@titulo" ha sido guardado.', ['@titulo' => $titulo]));
  }
}