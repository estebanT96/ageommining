<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 1. Data Capture and Cleaning
  $name_raw = strip_tags(trim($_POST['name']));
  // CAPITALIZATION: Standardizing name format
  $name     = ucwords(strtolower($name_raw)); 
  
  $email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $phone    = strip_tags(trim($_POST['phone']));
  $message  = strip_tags(trim($_POST['message']));
  
  $to        = 'ageommining2@gmail.com'; 
  $subject  = 'New Contact Message (EN) - Ageommining'; // Subject for the business

  // 2. Email body for the BUSINESS (Remittent)
  $body  = "You have received a new contact message from the English site:\n\n";
  $body .= "Name: $name\n";
  $body .= "Email: $email\n";
  $body .= "Phone: $phone\n";
  $body .= "Message: $message\n";

  // 3. Headers
  $headers  = "From: Ageommining <no-reply@ageommining.com>\r\n";
  $headers .= "Reply-To: $email\r\n";
  $headers .= "X-Mailer: PHP/" . phpversion();

  // 4. Basic Validation (Translated for Client)
  $errmsg = '';
  if (!$name) { $errmsg = 'Please enter your name'; }
  elseif (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errmsg = 'Please enter a valid email address'; }
  elseif (!$message) { $errmsg = 'Please enter your message'; }

  // 5. Send Process
  if (!$errmsg) {
    if (mail($to, $subject, $body, $headers)) {
      
      // AUTO-REPLY (Sent to the CLIENT in English)
      $auto_subject = "Thank you for contacting Ageommining";
      $auto_body    = "Hello $name,\n\nThank you for reaching out. We have successfully received your message and will get back to you as soon as possible.\n\nSincerely,\nThe Ageommining Team\nhttps://ageommining.com";
      mail($email, $auto_subject, $auto_body, $headers);

      // VISUAL RESPONSE (Success Page in English)
      echo '
      <!DOCTYPE html>
      <html lang="en">
      <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
          <meta http-equiv="refresh" content="5;url=../en/index.html">
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
              <h2>Message sent successfully!</h2>
              <p>Thank you for contacting us, <strong>' . htmlspecialchars($name) . '</strong>.<br>
              You will be redirected to the home page in <span class="timer">5 seconds</span>.</p>
          </div>
      </body>
      </html>';
    } else {
      echo '<div style="text-align:center; padding:50px; font-family:sans-serif;">Error sending the message. Please try again later.</div>';
    }
  } else {
    echo '<div style="text-align:center; padding:50px; font-family:sans-serif; color:red;">' . $errmsg . '</div>';
  }
}
?>