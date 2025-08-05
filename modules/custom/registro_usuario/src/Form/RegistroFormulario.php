<?php

namespace Drupal\registro_usuario\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Define un formulario que almacena datos en la base de datos.
 */
class RegistroFormulario extends FormBase {
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
    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => false,
    ];

    $form['correo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Correo'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Guardar'),
    ];

    return $form;
  }


  public function validateForm(array &$form, FormStateInterface $form_state){
    $nombre = $form_state->getValue('nombre');
    $correo = $form_state->getValue('correo');

    if ($nombre == null){
      $form_state->setErrorByName('nombre', $this->t("El campo de nombre no puede estar vacío."));
    }
    if (!preg_match('/@[^@]*\./', $correo)){
      $form_state->setErrorByName('correo', $this->t("El campo de correo no contiene un correo electrónico valido."));
    }

    $connection = Database::getConnection();
    $query = $connection->select('registro_usuario_datos', 'u')
      ->fields('u', ['id'])
      ->condition('correo', $correo)
      ->execute();
    $existe = $query->fetchField();
    if ($existe) {
      $form_state->setErrorByName('correo', $this->t("El correo electrónico ya está registrado en esta página."));
    }
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nombre = $form_state->getValue('nombre');
    $correo = $form_state->getValue('correo');
    
    // Insertar en la base de datos.
    $connection = Database::getConnection();
    $connection->insert('registro_usuario_datos')
      ->fields([
        'nombre' => $nombre,
        'correo' => $correo,
        'creado' => time(),
      ])
      ->execute();

    \Drupal::messenger()->addMessage($this->t('El nombre "@nombre" y correo "@correo" ha sido guardado.', ['@nombre' => $nombre,'@correo' => $correo]));
    \Drupal::logger('registro_usuario')->notice("Se ha registrado con éxito al usuario $nombre con el correo $correo usando el formulario creado.");
  }
}