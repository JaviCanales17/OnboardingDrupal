<?php

namespace Drupal\registro_usuario\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Define un formulario que almacena datos en la base de datos.
 */
class EdicionUsuarioFormulario extends FormBase {
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
    $usuario = $connection->select('registro_usuario_datos', 'm')
      ->fields('m', ['id',   'nombre', 'correo'])
      ->condition('id', $id)
      ->execute()
      ->fetchAssoc();


    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#value' => $usuario['nombre'],
      '#required' => TRUE,
    ];

    $form['correo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Correo'),
      '#value' => $usuario['correo'],
      '#required' => TRUE,
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
    $nombre = $form_state->getValue('nombre');
    $correo = $form_state->getValue('correo');
    
    // Insertar en la base de datos.
    $connection = Database::getConnection();
    $connection->update('registro_usuario_datos')
      ->fields([
        'nombre' => $nombre,
        'correo' => $correo,  
      ])
      ->condition('id', $id)
      ->execute();

    \Drupal::logger('registro_usuario')->notice("Se ha Editado con éxito al usuario $nombre con el correo $correo usando el formulario de edicion.");
    $form_state->setRedirect('registro_usuario.mensaje');
  }
}