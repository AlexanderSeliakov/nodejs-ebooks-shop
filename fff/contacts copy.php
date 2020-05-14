<?php include "head.php" ?>

<?php

    include "PHPMailer-master/src/PHPMailer.php";
    include "PHPMailer-master/src/SMTP.php";
    $mail =  new PHPMailer\PHPMailer\PHPMailer();
    
    $msg = "";
    $msgClass = "";
    
    if(isset($_POST["submit"])){

  filter_has_var(INPUT_POST, "submit");

  $name = $_POST["name"];
  $email = $_POST["mail"];
  $phone = $_POST["phone"];
  $message = $_POST["text"];

  if(!empty($email) && !empty($name) && !empty($message)){
  
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
      $msg = "please, use valid email";
      $msgClass = "bg-danger";
    }else{

      $mail->setFrom($email, $name);

      // Add a recipient.
      $mail->addAddress('to@alexander-seliakov.com');

      // Set the subject.
      $mail->Subject = 'You have new message from website';

      // message body
      $mail->Body = "
        Name: $name
        email: $email 
        Phone: $phone 
        Message: $message";

      // send the mail
      if($mail->send()){
        $msg = "Your email has been sent";
        $msgClass= "bg-success";
      }else{
        $msg = "Your email has not been sent";
        $msgClass = "bg-danger";
      };
    }
  }else{  
      $msg = "please, fill up the field";
      $msgClass = "bg-danger";
    }}
    ?>
    
<title>Contact form</title>
<link rel="stylesheet" href="css/all_books.css" media="all" type="text/css">
</head>

<body>

    <?php include "header.php";  ?>
    
    <main class="container my-5">

    <h3>Contact us</h3>

    <?php if($msg != ""):
              echo "<div class=' w-25 p-3 my-4 ".$msgClass ."'>".$msg."</div>";
    endif; ?>
    
        <div class=" mb-2">
            <div>
                <form action="" method="POST" class="contact-input">
                    <input class="form-control my-3" type="text" name="name" placeholder="name*" required>
                    <input class="form-control my-3" type="mail" name="mail" placeholder="email*" required>
                    <input class="form-control my-3" type="text" name="phone" placeholder="number">
                    <textarea class="form-control my-3" name="text" rows="4" placeholder="message*" required></textarea>
                    <button class="btn btn-outline-secondary my-3" type="submit" name="submit">Send</button>
                </form>
            </div>
        </div>
    </main>
    <?php include "footer.php" ?>
</body>

</html>