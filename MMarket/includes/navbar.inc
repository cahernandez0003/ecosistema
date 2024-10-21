<nav class="navbar navbar-expand-lg navbar-light bg-success">
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <!-- <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">INFORMACIÓN</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">CORPORATIVO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">ARTÍCULOS</a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        INGRESAR
                    </a>
                    <div class="dropdown-menu" style="width:300px">
                        <!-- Formulario de inicio de sesión -->
                        
                        <form action="" class="px-4 py-3" method="POST">
                            <div class="form-group">
                                <input type="text" class="form-control" name="username" placeholder="Usuario">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Contraseña">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-outline-success btn-block"> <i class="fa fa-sign-in-alt"></i> Ingresar </button>
                            </div>
                        </form>
                        <!-- Fin del formulario de inicio de sesión -->
                        <div class="dropdown-divider"></div>
                    </div>
                    <?php 
                        if ($_POST) {
                            $username = $_POST['username'];
                            $password  = md5($_POST['password']);
                            if(loginmm($con_mysql, $username, $password)) {
                                
                                if($_SESSION['urol'] == 'Admin') {
                                    echo "<script> window.location.replace('sage200/index.php'); </script>";
                                }
                            } else {
                                $_SESSION['type']    = 'danger';
                                $_SESSION['message'] = 'Los datos del Usuario son Incorrectos!';
                            }
                        }

                    ?>
                </li>
            </ul>
        </div>
        |
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="public/imgs/favicon.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
            MIMOUN MARKET SL
        </a>

        <li class="nav-item"><a class="btn btn-default" href="articles_pdf.php"><i class="fas fa-book"></i> &nbsp;CATALOGO</a>&nbsp; |</li>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

    </div>
</nav>