<aside class="main-sidebar sidebar-dark-orange elevation-4">
    <!-- Brand Logo -->
    <a href="index.html" class="brand-link navbar-danger">
        <img src="assets/img/{{$sidebar.data.app_logo}}" alt="ActRec Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><b>{{$head.data.app_name}}</b> <small style="color: #1a0000;">{{$head.data.version}}</small></span>
    </a>
    <div class="sidebar os-host os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition os-theme-light">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{$sidebar.data.user_image}}" class="img-circle elevation-2" alt="User Image" id="userImage">
            </div>
            <div class="info">
                <a href="#" class="d-block" id="userName">{{$sidebar.data.user_name}}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <li class="nav-header" role="menuitem" id="menuitem_1">TAREAS</li>
                <li class="nav-item" role="menuitem" id="menuitem_2">
                    <a href="#" class="nav-link" id="menuitem_2_submenu_1">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Actividades<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" id="menuitem_2_submenu_1_item_1">
                            <a href="?ctr=activities&mtd=lista" class="nav-link" id="menuitem_2_submenu_1_item_1_1">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Lista de Actividades</p>
                            </a>
                        </li>
                        <li class="nav-item" id="menuitem_2_submenu_1_item_2">
                            <a href="?ctr=activities&mtd=crear" class="nav-link" id="menuitem_2_submenu_1_item_1_2">
                                <i class="far fa-edit nav-icon"></i>
                                <p>Creaci&oacute;n de Actividad</p>
                            </a>
                        </li>
                        <li class="nav-item" id="menuitem_2_submenu_1_item_3">
                            <a href="?ctr=activities&mtd=crear_manual" class="nav-link" id="menuitem_2_submenu_1_item_1_3">
                                <i class="far fa-hand-paper nav-icon"></i>
                                <p>Creaci&oacute;n Manual</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header" role="menuitem" id="menuitem_3">ANALYTICS</li>
                <li class="nav-item" role="menuitem" id="menuitem_4">
                    <a href="#" class="nav-link" id="menuitem_4_submenu_1">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Diarios<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" id="menuitem_4_submenu_1_item_1">
                            <a href="?ctr=analytics&mtd=daily&prm=now" class="nav-link" id="menuitem_4_submenu_1_item_1_1">
                                <i class="far fa-clock nav-icon"></i>
                                <p>Hoy</p>
                            </a>
                        </li>
                        <li class="nav-item" id="menuitem_4_submenu_1_item_2">
                            <a href="#" class="nav-link" id="menuitem_4_submenu_2">
                                <i class="far fa-calendar nav-icon"></i>
                                <p>Por D&iacute;a<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item" id="menuitem_4_submenu_2_item_1">
                                    <a href="?ctr=analytics&mtd=daily&prm=monday" class="nav-link" id="menuitem_4_submenu_2_item_1_1">
                                        <i class="far fa-circle nav-icon"></i><p>Lunes</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_4_submenu_2_item_2">
                                    <a href="?ctr=analytics&mtd=daily&prm=tuesday" class="nav-link" id="menuitem_4_submenu_2_item_2_2">
                                        <i class="far fa-circle nav-icon"></i><p>Martes</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_4_submenu_2_item_3">
                                    <a href="?ctr=analytics&mtd=daily&prm=wednessday" class="nav-link" id="menuitem_4_submenu_2_item_3_3">
                                        <i class="far fa-circle nav-icon"></i><p>Mi&eacute;rcoles</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_4_submenu_2_item_4">
                                    <a href="?ctr=analytics&mtd=daily&prm=thursday" class="nav-link" id="menuitem_4_submenu_2_item_4_4">
                                        <i class="far fa-circle nav-icon"></i><p>Jueves</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_4_submenu_2_item_5">
                                    <a href="?ctr=analytics&mtd=daily&prm=friday" class="nav-link" id="menuitem_4_submenu_2_item_5_5">
                                        <i class="far fa-circle nav-icon"></i><p>Viernes</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_4_submenu_2_item_6">
                                    <a href="?ctr=analytics&mtd=daily&prm=saturday" class="nav-link" id="menuitem_4_submenu_2_item_6_6">
                                        <i class="far fa-circle nav-icon"></i><p>S&aacute;bado</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item" role="menuitem" id="menuitem_5">
                    <a href="#" class="nav-link" id="menuitem_5_submenu_1">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Semanales<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" id="menuitem_5_submenu_1_item_1">
                            <a href="?ctr=analytics&mtd=weekly&prm=uptoday" class="nav-link" id="menuitem_5_submenu_1_item_1_1">
                                <i class="fas fa-calendar-alt nav-icon" aria-hidden="true"></i>
                                <p>Esta semana</p>
                            </a>
                        </li>
                        <li class="nav-item" id="menuitem_5_submenu_1_item_2">
                            <a href="#" class="nav-link" id="menuitem_5_submenu_2">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Por Semana<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item" id="menuitem_5_submenu_2_item_1">
                                    <a href="?ctr=analytics&mtd=weekly&prm=first" class="nav-link" id="menuitem_5_submenu_2_item_1_1">
                                    <i class="far fa-circle nav-icon"></i><p>1° Semana</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_5_submenu_2_item_2">
                                    <a href="?ctr=analytics&mtd=weekly&prm=second" class="nav-link" id="menuitem_5_submenu_2_item_2_2">
                                    <i class="far fa-circle nav-icon"></i><p>2° Semana</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_5_submenu_2_item_3">
                                    <a href="?ctr=analytics&mtd=weekly&prm=third" class="nav-link" id="menuitem_5_submenu_2_item_3_3">
                                    <i class="far fa-circle nav-icon"></i><p>3° Semana</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_5_submenu_2_item_4">
                                    <a href="?ctr=analytics&mtd=weekly&prm=fourth" class="nav-link" id="menuitem_5_submenu_2_item_4_4">
                                    <i class="far fa-circle nav-icon"></i><p>4° Semana</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_5_submenu_2_item_5">
                                    <a href="?ctr=analytics&mtd=weekly&prm=fifth" class="nav-link" id="menuitem_5_submenu_2_item_5_5">
                                    <i class="far fa-circle nav-icon"></i><p>5° Semana</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item" role="menuitem" id="menuitem_6">
                    <a href="#" class="nav-link" id="menuitem_6_submenu_1">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Mensuales<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" id="menuitem_6_submenu_1_item_1">
                            <a href="?ctr=analytics&mtd=monthly&prm=uptoday" class="nav-link" id="menuitem_6_submenu_1_item_1_1">
                                <p>Este mes</p>
                            </a>
                        </li>
                        <li class="nav-item" id="menuitem_6_submenu_1_item_2">
                            <a href="#" class="nav-link" id="menuitem_6_submenu_2">
                                <p>Por Mes<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item" id="menuitem_6_submenu_2_item_1">
                                    <a href="?ctr=analytics&mtd=monthly&prm=junary" class="nav-link" id="menuitem_6_submenu_2_item_1_1">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Enero</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_2">
                                    <a href="?ctr=analytics&mtd=monthly&prm=february" class="nav-link" id="menuitem_6_submenu_2_item_2_2">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Febrero</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_3">
                                    <a href="?ctr=analytics&mtd=monthly&prm=march" class="nav-link" id="menuitem_6_submenu_2_item_3_3">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Marzo</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_4">
                                    <a href="?ctr=analytics&mtd=monthly&prm=april" class="nav-link" id="menuitem_6_submenu_2_item_4_4">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Abril</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_5">
                                    <a href="?ctr=analytics&mtd=monthly&prm=may" class="nav-link" id="menuitem_6_submenu_2_item_5_5">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Mayo</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_6">
                                    <a href="?ctr=analytics&mtd=monthly&prm=june" class="nav-link" id="menuitem_6_submenu_2_item_6_6">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Junio</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_7">
                                    <a href="?ctr=analytics&mtd=monthly&prm=july" class="nav-link" id="menuitem_6_submenu_2_item_7_7">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Julio</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_8">
                                    <a href="?ctr=analytics&mtd=monthly&prm=august" class="nav-link" id="menuitem_6_submenu_2_item_8_8">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Agosto</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_9">
                                    <a href="?ctr=analytics&mtd=monthly&prm=september" class="nav-link" id="menuitem_6_submenu_2_item_9_9">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Septiembre</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_10">
                                    <a href="?ctr=analytics&mtd=monthly&prm=october" class="nav-link" id="menuitem_6_submenu_2_item_10_10">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Octubre</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_11">
                                    <a href="?ctr=analytics&mtd=monthly&prm=november" class="nav-link" id="menuitem_6_submenu_2_item_11_11">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Noviembre</p>
                                    </a>
                                </li>
                                <li class="nav-item" id="menuitem_6_submenu_2_item_12">
                                    <a href="?ctr=analytics&mtd=monthly&prm=Dicember" class="nav-link" id="menuitem_6_submenu_2_item_12_12">
                                        <i class="far fa-dot-circle nav-icon"></i><p>Diciembre</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-header" role="menuitem" id="menuitem_7">COMPONENTES</li>
                <li class="nav-item" role="menuitem" id="menuitem_8">
                    <a href="#" class="nav-link" id="menuitem_8_submenu_1">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>Tipos de Actividades<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" id="menuitem_8_submenu_1_item_1">
                            <a href="?ctr=activitycases&mtd=index" class="nav-link" id="menuitem_8_submenu_1_item_1_1">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Lista de tipos</p>
                            </a>
                        </li>
                        <li class="nav-item" id="menuitem_8_submenu_1_item_2">
                            <a href="?ctr=activitycases&mtd=crear" class="nav-link" id="menu_5_submenu_1_item_2_2">
                                <i class="far fa-edit nav-icon"></i>
                                <p>Crear nuevo</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item" role="menuitem" id="menuitem_9">
                    <a href="#" class="nav-link" id="menuitem_9_submenu_1">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Areas<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" id="menuitem_9_submenu_1_item_1">
                            <a href="?ctr=areas&mtd=index" class="nav-link" id="menuitem_9_submenu_1_item_1_1">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Lista de tipos</p>
                            </a>
                        </li>
                        <li class="nav-item" id="menuitem_9_submenu_1_item_2">
                            <a href="?ctr=areas&mtd=crear" class="nav-link" id="menuitem_9_submenu_1_item_2_2">
                                <i class="far fa-edit nav-icon"></i>
                                <p>Crear nuevo</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{* <!-- <li class="nav-item">
                    <a href="dashboard.html" class="nav-link">
                        <i class="fas fa-chart-line nav-icon"></i>
                        <p>
                            analytics
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li> --> *}}
                
                {{* <li class="nav-header">REPORTES</li>
                <li class="nav-item" id="menu_7">
                    <a href="#" class="nav-link" id="level1_menu2_submenu1">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Generales<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="?ctr=reports&mtd=daily" class="nav-link" id="level1_menu2_submenu1_item1">
                                <i class="far fa-edit nav-icon"></i>
                                <p>Actividad Diaria</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?ctr=reports&mtd=weekly" class="nav-link" id="level1_menu2_submenu1_item2">
                                <i class="far fa-edit nav-icon"></i>
                                <p>Actividad Semanal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?ctr=reports&mtd=monthly" class="nav-link" id="level1_menu1_submenu1_item3">
                                <i class="far fa-edit nav-icon"></i>
                                <p>Actividad Mensual</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-circle"></i>
                        <p>Level 1<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Level 2<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> *}}
            </ul>
        </nav>
    </div>
</aside>