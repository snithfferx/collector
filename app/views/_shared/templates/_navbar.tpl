<header>

    <!-- INICIO DE HEADER -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <span class="navbar-brand mb-0 h1"><b>Configuración de Tienda en Línea</b></span>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="../">Home <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="http://10.0.10.35/Views/precios.php">Precios</a>
                <a class="nav-item nav-link" href="http://10.0.10.35/Views/combos.php">Combos</a>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Colecciones
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/collections/read">Lista</a>
                        <a class="dropdown-item" href="/collections/download">Descargar</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="http://10.0.10.35/Views/colecciones.php">Admin</a>
                    </div>
                </li>
                {{* <a class="nav-item nav-link" href="http://10.0.10.35/Views/actualizacion.php">Actualización
                    <b><span id="spannotupdated" class="badge badge-warning" style="color: white"></span></b>
                </a> *}}
                <a class="nav-item nav-link" href="/orders/index">
                    Ordenes
                    <b>
                        <span id="lastOrders" class="badge badge-warning" style="color: white"></span>
                    </b>
                </a>
                <a class="nav-item nav-link" href="/products/index">Edicion en masa</a>
            </div>
        </div>
    </nav>
</header>