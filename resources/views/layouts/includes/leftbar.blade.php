<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul>
                <li class="text-muted menu-title">Módulos</li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-shopping-cart"></i>
                        <span> Comercial </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url("caixa") }}">Totais de Caixa</a></li>
                        <li><a href="{{ url("vale-compra") }}">Vale Compras</a></li>
                        <li><a href="{{ url("vale-compra-modelo") }}">Modelos de Vale</a></li>
                        <li><a href="{{ url("meta") }}">Meta</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-puzzle-piece"></i>
                        <span> Produtos </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">

                        <li><a href="{{ url('produto') }}">Cadastro</a><li>
                        <li><a href="{{ url('produto/quiosque') }}">Consulta</a><li>
                        <li><a href="{{ url('produto-historico-preco') }}">Histórico de Preços</a></li>
		        <li><a href="{{ url('marca') }}">Marcas</a><li>
		        <li><a href="{{ url('secao-produto') }}">Seções</a><li>
		        <li><a href="{{ url('tipo-produto') }}">Tipos</a></li>
		        <li><a href="{{ url('unidade-medida') }}">Unidades de medida</a></li>
		        <li><a href="{{ url('ncm') }}">NCM</a></li>

                    </ul>
                </li>


                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-truck"></i>
                        <span> Estoque </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">

	                <li><a href="{{ url('estoque-saldo') }}">Saldos</a></li>
	                <li><a href="{{ url('estoque-saldo/relatorio-analise-filtro') }}">Análise</a></li>
	                <li><a href="{{ url('estoque-saldo/relatorio-comparativo-vendas-filtro') }}">Venda Filial x Depósito</a></li>
	                <li><a href="{{ url('estoque-saldo/relatorio-fisico-fiscal-filtro') }}">Fisico x Fiscal</a></li>
	                <li><a href="{{ url('estoque-saldo-conferencia') }}">Conferência</a></li>

                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-credit-card fa-ban"></i>
                        <span> Cheques </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('cheque') }}">Cadastro</a></li>
                        <li><a href="{{ url('cheque-repasse') }}">Repasses</a></li>
                        <li><a href="{{ url('cheque-motivo-devolucao') }}">Motivos de Devolução</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-users"></i>
                        <span> Usuários </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('usuario') }}">Cadastro</a></li>
                        <li><a href="{{ url('grupo-usuario') }}">Grupos</a></li>
                        <li><a href="{{ url('permissao') }}">Permissões</a></li>
                    </ul>
                </li>
		
                <li><a href="{{ url('usuario') }}"><i class="fa fa-image"></i><span>Imagens</span></a></li>



<!--
		<hr>

                <li class="has_sub">
                    <a href="{{ url('') }}" class="waves-effect"><span
                            class="label label-pill label-primary pull-xs-right">1</span><i
                            class="zmdi zmdi-view-dashboard"></i><span> Dashboard </span> </a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-format-underlined"></i>
                        <span> User Interface </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="ui-buttons.php">Buttons</a></li>
                        <li><a href="ui-cards.php">Cards</a></li>
                        <li><a href="ui-dropdowns.php">Dropdowns</a></li>
                        <li><a href="ui-checkbox-radio.php">Checkboxs-Radios</a></li>
                        <li><a href="ui-navs.php">Navs</a></li>
                        <li><a href="ui-progress.php">Progress</a></li>
                        <li><a href="ui-modals.php">Modals</a></li>
                        <li><a href="ui-notification.php">Notification</a></li>
                        <li><a href="ui-alerts.php">Alerts</a></li>
                        <li><a href="ui-carousel.php">Carousel</a></li>
                        <li><a href="ui-bootstrap.php">Bootstrap UI</a></li>
                        <li><a href="ui-typography.php">Typography</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-album"></i>
                        <span> Components </span>
                        <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="components-grid.php">Grid</a></li>
                        <li><a href="components-range-sliders.php">Range sliders</a></li>
                        <li><a href="components-sweet-alert.php">Sweet Alerts</a></li>
                        <li><a href="components-ratings.php">Ratings</a></li>
                        <li><a href="components-treeview.php">Tree View</a></li>
                        <li><a href="components-tour.php">Tour</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="calendar.php" class="waves-effect"><i class="zmdi zmdi-calendar"></i><span> Calendar </span> </a>
                </li>


                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-widgets"></i>
                        <span> Widgets </span>
                        <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="widgets-tiles.php">Tile Box</a></li>
                        <li><a href="widgets-charts.php">Chart Widgets</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-fire"></i>
                        <span> Icons </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="icons-materialdesign.php">Material Design</a></li>
                        <li><a href="icons-ionicons.php">Ion Icons</a></li>
                        <li><a href="icons-fontawesome.php">Font awesome</a></li>
                        <li><a href="icons-themify.php">Themify Icons</a></li>
                        <li><a href="icons-simple-line.php">Simple line Icons</a></li>
                        <li><a href="icons-weather.php">Weather Icons</a></li>
                        <li><a href="icons-pe7.php">PE7 Icons</a></li>
                        <li><a href="icons-typicons.php">Typicons</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><span
                            class="label label-pill label-warning pull-xs-right">8</span><i
                            class="zmdi zmdi-collection-text"></i><span> Forms </span> </a>
                    <ul class="list-unstyled">
                        <li><a href="form-elements.php">General Elements</a></li>
                        <li><a href="form-advanced.php">Advanced Form</a></li>
                        <li><a href="form-validation.php">Form Validation</a></li>
                        <li><a href="form-pickers.php">Form Pickers</a></li>
                        <li><a href="form-wizard.php">Form Wizard</a></li>
                        <li><a href="form-mask.php">Form Masks</a></li>
                        <li><a href="form-uploads.php">Multiple File Upload</a></li>
                        <li><a href="form-xeditable.php">X-editable</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i
                            class="zmdi zmdi-format-list-bulleted"></i> <span> Tables </span> <span
                            class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="tables-basic.php">Basic Tables</a></li>
                        <li><a href="tables-datatable.php">Data Table</a></li>
                        <li><a href="tables-responsive.php">Responsive Table</a></li>
                        <li><a href="tables-tablesaw.php">Tablesaw</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i
                            class="zmdi zmdi-chart"></i><span> Charts </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="chart-flot.php">Flot Chart</a></li>
                        <li><a href="chart-morris.php">Morris Chart</a></li>
                        <li><a href="chart-chartjs.php">Chartjs</a></li>
                        <li><a href="chart-peity.php">Peity Charts</a></li>
                        <li><a href="chart-chartist.php">Chartist Charts</a></li>
                        <li><a href="chart-c3.php">C3 Charts</a></li>
                        <li><a href="chart-sparkline.php">Sparkline charts</a></li>
                        <li><a href="chart-knob.php">Jquery Knob</a></li>
                    </ul>
                </li>

                <li class="text-muted menu-title">More</li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><span
                            class="label label-success label-pill pull-xs-right">13</span><i
                            class="zmdi zmdi-collection-item"></i><span> Pages </span></a>
                    <ul class="list-unstyled">
                        <li><a href="pages-starter.php">Starter Page</a></li>
                        <li><a href="pages-login.php">Login</a></li>
                        <li><a href="pages-register.php">Register</a></li>
                        <li><a href="pages-recoverpw.php">Recover Password</a></li>
                        <li><a href="pages-lock-screen.php">Lock Screen</a></li>
                        <li><a href="pages-404.php">Error 404</a></li>
                        <li><a href="pages-500.php">Error 500</a></li>
                        <li><a href="pages-timeline.php">Timeline</a></li>
                        <li><a href="pages-invoice.php">Invoice</a></li>
                        <li><a href="pages-pricing.php">Pricing</a></li>
                        <li><a href="pages-gallery.php">Gallery</a></li>
                        <li><a href="pages-maintenance.php">Maintenance</a></li>
                        <li><a href="pages-comingsoon.php">Coming Soon</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-blur-linear"></i><span>Multi Level </span>
                        <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><span>Menu Level 1.1</span> <span
                                    class="menu-arrow"></span> </a>
                            <ul style="">
                                <li><a href="javascript:void(0);"><span>Menu Item</span></a></li>
                                <li><a href="javascript:void(0);"><span>Menu Item</span></a></li>
                                <li><a href="javascript:void(0);"><span>Menu Item</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><span>Menu Level 1.2</span></a>
                        </li>
                    </ul>
                </li>
-->
            </ul>
            <div class="clearfix"></div>
        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>

</div>
<!-- Left Sidebar End -->
