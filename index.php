<?php

require_once 'lib/error.php';
require_once 'lib/db.php';
require_once 'lib/jwt.php';

allow_methods(['GET']);

$dbconn = make_pg_connection();
$user = null;

if (isset($_COOKIE['token'])) {
  $ok = false;
  $token = $_COOKIE['token'];
  $decoded = read_token($token, $ok);

  if ($ok) {
    if (isset($decoded->user)) {
      $user = getuserbyid($dbconn, $decoded->user);
      if ($user === null) {
        $ok = false;
      }
    } else {
      $ok = false;
    }
  }

  if (!$ok) {
    setcookie('token', false);
  }
}

$error = get_friendly_error_message();

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.3/dist/css/foundation.min.css"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.3/dist/js/foundation.min.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <main>
      <div class="card login-card">
        <div class="card-divider">
          <h4>
          <?php
          if ($user !== null) {
            echo 'Bienvenido';
          } else {
            echo 'Iniciar sesión';
          }
          ?>
          </h4>
        </div>
        <div class="card-section">
          <?php
          if (isset($error)) {
          ?>
          <div class="callout alert" data-closable>
            <span><?php echo htmlspecialchars($error); ?></span>
            <button class="close-button" aria-label="Cerrar alerta" type="button" data-close>
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php
          }

          if ($user !== null) {
          ?>
          <p class="text-right">
            Inició sesión como
            <code>
              <?php echo htmlspecialchars($user['login']); ?>
            </code>
          </p>
          <div class="grid-container full">
            <div class="grid-y">
              <div class="cell">
                <label>
                  Nombres
                  <input
                    type="text"
                    value="<?php echo htmlspecialchars($user['name']); ?>"
                    readonly
                  />
                </label>
              </div>
              <div class="cell">
                <label>
                  Apellidos
                  <input
                    type="text"
                    value="<?php echo htmlspecialchars($user['lastname']); ?>"
                    readonly
                  />
                </label>
              </div>
              <div class="cell">
                <label>
                  Dirección
                  <input
                    type="text"
                    value="<?php echo htmlspecialchars($user['address']); ?>"
                    readonly
                  />
                </label>
              </div>
              <div class="cell">
                <form action="logout.php" method="POST">
                  <input type="submit" value="Salir" class="alert button" />
                </form>
              </div>
            </div>
          </div>
          <?php
          } else {
          ?>
          <p>Ingrese sus credenciales para continuar.</p>
          <form action="login.php" method="POST">
            <div class="grid-container full">
              <div class="grid-y">
                <div class="cell">
                  <label>
                    Usuario
                    <input type="text" name="login" required />
                  </label>
                </div>
                <div class="cell">
                  <label>
                    Contraseña
                    <input type="password" name="password" required />
                  </label>
                </div>
                <div class="cell">
                  <input type="submit" value="Ingresar" class="button" />
                </div>
              </div>
            </div>
          </form>
          <?php
          }
          ?>
        </div>
      </div>
    </main>
    <script>
      $(document).foundation()
    </script>
  </body>
</html>
<?php

clear_error();

?>
