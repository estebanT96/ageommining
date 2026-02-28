<?php
if (isset($_POST["action"])) {
  // 1. Capture and Clean Data
  $name    = strip_tags(trim($_POST['name']));
  $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $phone   = strip_tags(trim($_POST['phone']));
  $message = strip_tags(trim($_POST['message']));
  
  $to      = 'ageommining2@gmail.com'; 
  $subject = 'Nuevo Mensaje de Contacto - Ageommining';

  // 2. Build the Email Body (Removed $website to avoid errors)
  $body  = "Has recibido un nuevo mensaje:\n\n";
  $body .= "Nombre: $name\n";
  $body .= "E-Mail: $email\n";
  $body .= "Teléfono: $phone\n";
  $body .= "Mensaje: $message\n";

  // 3. Set Proper Headers
  $headers  = "From: Web Contact <no-reply@ageommining.com>\r\n";
  $headers .= "Reply-To: $email\r\n";
  $headers .= "X-Mailer: PHP/" . phpversion();

  // 4. Validation
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

  // 5. Send Process
  $result = '';
  if (!$errmsg) {
    // CHANGED: Using $headers instead of $from
    if (mail($to, $subject, $body, $headers)) {
      $result = '<div class="alert alert-success">Gracias por contactarnos. ¡Mensaje enviado con éxito!</div>';
    } else {
      $result = '<div class="alert alert-danger">Error al enviar el mensaje. Inténtelo más tarde.</div>';
    }
  } else {
    $result = '<div class="alert alert-danger">' . $errmsg . '</div>';
  }
  
  echo $result;
}
?>