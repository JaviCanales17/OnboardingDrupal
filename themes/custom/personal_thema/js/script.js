(function (Drupal, once) {
  Drupal.behaviors.validarEmail = {
    attach(context, settings) {
      once('validarEmail', '#registro-bloque', context).forEach(function (form) {
        form.addEventListener('submit', function (e) {
          const emailInput = form.querySelector('input[name="correo"]');
          const email = emailInput.value;
          const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

          if (!regex.test(email)) {
            alert('Por favor, ingresa un correo electrónico válido.');
            emailInput.focus();
            e.preventDefault();
          }
        });
      });
    },
  };
})(Drupal, once);