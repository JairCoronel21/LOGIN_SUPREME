<?php


      require_once ("clsMailer.php");


      $mailSend = new clsMail();
 
      $bodyHTML = '<h2>Hola correo envaido desde PHP</h2> 
      ';

      $enviado = $mailSend->metEnviar("Aviso", "jair", "jilmercoronel.16@gmail.com","AsuntoX", $bodyHTML);

      if($enviado){
         echo 'Enviado';


      }else {
          echo  'No se puede enviar';
      }