<?php
	// traer los elementos necesarios 
	require 'database/conexion.php';
	require 'controller/funcs.php';
	
	$errors = array();

	// iniciar el metodo post
	
	if(!empty($_POST)) {
		// Declarando las variables
       // real_scape nos ayuda a  limpiar la cadena y tener el formulario seguro
	   // con los elemntos nombrados en html
        $nombre = $mysqli->real_escape_string($_POST['nombre']);
		$usuario = $mysqli->real_escape_string($_POST['usuario']);
		$password = $mysqli->real_escape_string($_POST['password']);
		$con_password = $mysqli->real_escape_string($_POST['con_password']);
		$email= $mysqli->real_escape_string($_POST['email']);
		// se genera un nuevo elemnto captha 
		$captcha= $mysqli->real_escape_string($_POST['g-recaptcha-response']);
        // por defecto es estado del usuario es inactivo
		$activo = 1;
		//tipo de usuario normal
		//privilegio de usurio normal
		$tipo_usuario = 2;
		// clave secreta del captha
		$secret = '6Lcv7akhAAAAAAFVsKn1L8JpGwt3gBlk2lseul9J';
        
        // validacion de la cpatha 
        if(!$captcha) {
            $errors[] = "Por favor verifica el captcha";
        }
        
		// validar lod datos en require html
        if(isNull($nombre, $usuario, $password, $con_password, $email)) {
            $errors[] = "Debe llenar todos los campos";
        }

        // llama a la funcion para validar email
        if(!isEmail($email)) {
            $errors[] = "Direccion de correo invalida";
        }
        
		// validar el password 
        if(!validaPassword($password, $con_password)) {
            $errors[] = "Las contraseñas no coinciden";

        }

        if(usuarioExiste($usuario)) {
            $errors[] = "El nombre de usuario $usuario ya existe";

        }


        if(emailExiste($email)) {
            $errors[] = "El correo electronico $email ya existe";
        }

            // validar los errores contabilizados
        if(count($errors) == 0) {
			// esto para validar el captha 
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");

             // recibir el json
            $arr = json_decode($response, TRUE);

			//empezar a registrar al usuario
            if($arr['success']) {
				// cifrar la contraseña 
                $pass_hash = hashPassword($password);
				// generar token de entrada 
                $token = generateToken();


				// si se cumple se ingrsan los datos de las variables 
				//llama a la función
                $registro = registraUsuario($usuario, $pass_hash, $nombre, $email,
                $activo, $token, $tipo_usuario);
                  
                if($registro > 0) {
                   
                    $url = 'http://'.$_SERVER["SERVER_NAME"].
					'/LOGIN_SUPREME/activar.php?id='.$registro.'&val='.$token;

                    $asunto = 'Activar Cuenta - Sistema de Usuarios';
							$cuerpo = "Estimado $nombre: <br /><br />Para continuar
							con el proceso de registro, es indispensable de click en
							la siguiente liga <a href='$url'>Activar Cuenta</a>";


                    if (enviarEmail($email, $nombre, $asunto, $cuerpo)) {
                        echo "Para terminar el proceso de registro siga las
						instrucciones que le hemos enviado la dirección de correo 
						electronico: $email";

                        echo "<br><a href='index.php' >Iniciar Sesión</a>";
                        exit;
                         
                    } else {
                        $errors[] = "Error al Enviar Correo";
                    }

                }

            }else {
				$errors[] = 'Error al comprobar captcha';
			}
        } else {
       
			$errors[] = "Error al Registrar";
  
    } 
    }
	
?>

<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Registro</title>
		
		<link rel="stylesheet" href="css/bootstrap.min.css" >
		<link rel="stylesheet" href="css/bootstrap-theme.min.css" >
		
        <script type="text/javascript">
		    document.onkeydown=function (e){
        var currKey=0,evt=e||window.event;
        currKey=evt.keyCode||evt.which||evt.charCode;
        if (currKey == 123) {
            window.event.cancelBubble = true;
            window.event.returnValue = false;
        }
    }

         
		</script>
		<script src="js/bootstrap.min.js" ></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	
	<body onkeydown="return">
		<div class="container">
			<div id="signupbox" style="margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="panel-title">Reg&iacute;strate</div>
						<div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="index.php">Iniciar Sesi&oacute;n</a></div>
					</div>  
					
					<div class="panel-body" >
						
						<form id="signupform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
							
							<div id="signupalert" style="display:none" class="alert alert-danger">
								<p>Error:</p>
								<span></span>
							</div>
							
							<div class="form-group">
								<label for="nombre" class="col-md-3 control-label">Nombre:</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="nombre" placeholder="Nombre" value="<?php if(isset($nombre)) echo $nombre; ?>" required >
								</div>
							</div>
							
							<div class="form-group">
								<label for="usuario" class="col-md-3 control-label">Usuario</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="usuario" placeholder="Usuario" value="<?php if(isset($usuario)) echo $usuario; ?>" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="password" class="col-md-3 control-label">Password</label>
								<div class="col-md-9">
									<input type="password" class="form-control" name="password" placeholder="Password" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="con_password" class="col-md-3 control-label">Confirmar Password</label>
								<div class="col-md-9">
									<input type="password" class="form-control" name="con_password" placeholder="Confirmar Password" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="email" class="col-md-3 control-label">Email</label>
								<div class="col-md-9">
									<input type="email" class="form-control" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>" required>
								</div>
							</div>

							
							<div class="form-group">
								<label for="captcha" class="col-md-3 control-label"></label>
								<div class="g-recaptcha col-md-9" data-sitekey="6Lcv7akhAAAAAAc-pPCutQ0GUKkhDUmu2an-h4d9"></div>
							</div>
							
							<div class="form-group">                                      
								<div class="col-md-offset-3 col-md-9">
									<button id="btn-signup" type="submit" class="btn btn-info"><i class="icon-hand-right"></i>Registrar</button> 
								</div>
							</div>
						</form>
						<?php echo resultBlock($errors); ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>															