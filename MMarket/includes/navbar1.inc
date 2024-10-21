<style>

    nav{
      background-color: #FFFFFF;
        background-size: 400%;
    }

    ul li{
        border-radius: 3px;
        margin:1px;
        background-color: #981932;
        
    }

    ul li:hover{
      
        background-color: #009C46;
    }

    @keyframes change {
        0%{
            background-position: 0 50%;
        }
        50%{
            background-position: 100% 50%;
        }
        100%{
            background-position: 0 50%;
        }
    }
</style>
   <nav class="navbar navbar-expand-lg navbar-dark" style="position: fixed; z-index: 1; height: 60px; width: 100%;">
    <img src="public/imgs/favicon.png" style="width: 55px; height: 55px; padding: 0px; margin: 20px;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01" style="font-family: cursive;">
        <a style="color: black;" class="navbar-brand" href="#">&nbsp; <strong>MIMOUN MARKET SL</strong></a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active" style="background-color: #343A60">
                <a class="nav-link" href="./pages/login.php"> INGRESAR<span class="sr-only">(current)</span></a>
            </li>
        </ul>
            <!-- FRACCIÓN DE CÓDIGO LOGIN -->
        <div class="dropdown" style="padding: 5px; border-radius: 10px;">
            <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-style: none;">
            Ingresar
            </button>
            <div class="dropdown-menu" style="background-color: #009C46" aria-labelledby="dropdownMenu2">
                <div class="dropdown-item" style="background-color: #009C46;">
                    <div class="dropdown">
                        <form class="px-4 py-3" style="width: 355px; background-color: #D62718;" method="post">
                            <div class="form-group">
                                <input type="email" class="form-control" name="username" id="exampleDropdownFormEmail1" placeholder="usuario" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="exampleDropdownFormPassword1" name="password" placeholder="Contraseña" required>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="dropdownCheck">
                                    <label class="form-check-label" for="dropdownCheck">Recuerdame</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-block" style="">Ingresar</button>
                        </form>
                        <a class="dropdown-item" href="pages/register.php"><i class="fa fa-edit"></i> Unirse a la comunidad</a>
                        <a class="dropdown-item" href="#">Recuperar contraseña?</a>
                    </div>
                </div>
                <?php
                    if ($_POST) {
                        $username = $_POST['username'];
                        $password  = md5($_POST['password']);
                        if(login($con, $username, $password)) {
                            if($_SESSION['urol'] == 'Admin') {
                                echo "<script> window.location.replace('pages/users.php'); </script>";
                            } else if($_SESSION['urol'] == 'Instructor') {
                                echo "<script> window.location.replace('pages/instructor.php'); </script>";
                            } else if($_SESSION['urol'] == 'Admin-full') {
                                echo "<script> window.location.replace('pages/administrator.php'); </script>";
                            }
                        } else {
                            $_SESSION['type']    = 'danger';
                            $_SESSION['message'] = 'Los datos del Usuario son Incorrectos!';
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</nav>