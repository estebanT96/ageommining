<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 1. Captura y Limpieza de Datos
  $name_raw = strip_tags(trim($_POST['name']));
  // CAPITALIZACIÓN: Convertimos a minúsculas y luego ponemos en mayúscula la primera letra de cada palabra
  $name     = ucwords(strtolower($name_raw));

  $email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $phone    = strip_tags(trim($_POST['phone']));
  $message  = strip_tags(trim($_POST['message']));

  $to       = 'ageommining2@gmail.com';
  $subject  = 'Nuevo Mensaje de Contacto - Ageommining';

  // 2. Cuerpo del mensaje para TI (Nombre Capitalizado)
  $body  = "Has recibido un nuevo mensaje de contacto:\n\n";
  $body .= "Nombre: $name\n";
  $body .= "E-Mail: $email\n";
  $body .= "Teléfono: $phone\n";
  $body .= "Mensaje: $message\n";

  // 3. Encabezados (Headers)
  $headers  = "From: Ageommining <no-reply@ageommining.com>\r\n";
  $headers .= "Reply-To: $email\r\n";
  $headers .= "X-Mailer: PHP/" . phpversion();

  // 4. Validación básica
  $errmsg = '';
  if (!$name) {
    $errmsg = 'Por favor ingrese su nombre';
  }
  if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errmsg = 'Por favor ingrese un email válido';
  }
  if (!$message) {
    $errmsg = 'Por favor ingrese su mensaje';
  }

  // 5. Proceso de Envío
  if (!$errmsg) {
    if (mail($to, $subject, $body, $headers)) {

      // AUTO-RESPUESTA (Nombre Capitalizado)
      $auto_subject = "Gracias por contactar a Ageommining";
      $auto_body    = "Hola $name,\n\nGracias por escribirnos. Hemos recibido tu mensaje correctamente y te contactaremos a la brevedad.\n\nAtentamente,\nEl equipo de Ageommining\nhttps://ageommining.com";
      mail($email, $auto_subject, $auto_body, $headers);

      // RESPUESTA VISUAL (Nombre Capitalizado)
      echo '
      <!DOCTYPE html>
      <html lang="es">
      <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
          <meta http-equiv="refresh" content="5;url=../index.html">
          <style>
              body { margin: 0; padding: 0; font-family: "Roboto", sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f8f9fa; color: #333; }
              .container { text-align: center; padding: 40px; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 12px; max-width: 500px; width: 90%; }
              h2 { color: #00008b; margin-bottom: 10px; }
              .loader { border: 6px solid #f3f3f3; border-top: 6px solid #00008b; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 20px auto; }
              @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
              .timer { font-weight: bold; color: #fcbe1b; }
          </style>
      </head>
      <body>
          <div class="container">
              <div class="loader"></div>
              <h2>¡Mensaje enviado con éxito!</h2>
              <p>Gracias por contactarnos, <strong>' . htmlspecialchars($name) . '</strong>.<br>
              Serás redireccionado al inicio en <span class="timer">5 segundos</span>.</p>
          </div>
      </body>
      </html>';
    } else {
      echo '<div style="text-align:center; padding:50px; font-family:sans-serif;">Error al enviar el mensaje. Inténtelo más tarde.</div>';
    }
  } else {
    echo '<div style="text-align:center; padding:50px; font-family:sans-serif; color:red;">' . $errmsg . '</div>';
  }
}
