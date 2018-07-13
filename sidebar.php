				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">
				
				    <div class="nano">
				        <div class="nano-content">
				            <nav id="menu" class="nav-main" role="navigation">
				            
				                <ul class="nav nav-main">
				                    <li>
				                        <a href="./">
				                            <i class="fa fa-home" aria-hidden="true"></i>
				                            <span>Painel</span>
				                        </a>                        
				                    </li>
				                    <?php if (in_array($_SESSION['role_id'], array(ADMINISTRADOR))) { ?>
				                    <li class="nav-parent">
				                        <a href="#">
				                            <i class="fa fa-user-circle" aria-hidden="true"></i>
				                            <span>Usuários</span>
				                        </a>
				                        <ul class="nav nav-children">
				                            <li>
				                                <a href="usuario.php?mode=list">
				                                    Listar
				                                </a>
				                            </li>
				                            <li>
				                                <a href="usuario.php?mode=add">
				                                    Cadastrar
				                                </a>
				                            </li>				                            
				                        </ul>

				                    </li>				                    
				                    <li class="nav-parent">
				                        <a href="#">
				                            <i class="fa fa-address-card" aria-hidden="true"></i>
				                            <span>Clientes</span>
				                        </a>
				                        <ul class="nav nav-children">
				                            <li>
				                                <a href="client.php?mode=list">
				                                    Listar
				                                </a>
				                            </li>
				                            <li>
				                                <a href="client.php?mode=add">
				                                    Cadastrar
				                                </a>
				                            </li>				                            
				                        </ul>

				                    </li>
				                    <?php } ?>				  

							        <?php if (in_array($_SESSION['role_id'], array(ADMINISTRADOR, TECNICO))) { ?>
				                    <li class="nav-parent">
				                        <a href="#">
				                            <i class="fa fa-cogs" aria-hidden="true"></i>
				                            <span>Tipos de Equipamento</span>
				                        </a>
				                        <ul class="nav nav-children">
				                            <li>
				                                <a href="equip_types.php?mode=list">
				                                    Listar
				                                </a>
				                            </li>
				                            <li>
				                                <a href="equip_types.php?mode=add">
				                                    Cadastrar
				                                </a>
				                            </li>				                            
				                        </ul>

				                    </li>				                    
				                    <li class="nav-parent">
				                        <a href="#">
				                            <i class="fa fa-cogs" aria-hidden="true"></i>
				                            <span>Tipos de Problemas</span>
				                        </a>
				                        <ul class="nav nav-children">
				                            <li>
				                                <a href="problema.php?mode=list">
				                                    Listar
				                                </a>
				                            </li>
				                            <li>
				                                <a href="problema.php?mode=add">
				                                    Cadastrar
				                                </a>
				                            </li>				                            
				                        </ul>

				                    </li>				                    
				                    <li class="nav-parent">
				                        <a href="#">
				                            <i class="fa fa-cogs" aria-hidden="true"></i>
				                            <span>Equipamentos</span>
				                        </a>
				                        <ul class="nav nav-children">
				                            <li>
				                                <a href="equipments.php?mode=list">
				                                    Listar
				                                </a>
				                            </li>
				                            <li>
				                                <a href="equipments.php?mode=add">
				                                    Cadastrar
				                                </a>
				                            </li>				                            
				                        </ul>

				                    </li>				                    

				                    <li class="nav-parent">
				                        <a href="#">
				                            <i class="fa fa-industry" aria-hidden="true"></i>
				                            <span>Pontos</span>
				                        </a>
				                        <ul class="nav nav-children">
				                            <li>
				                                <a href="ponto.php?mode=list">
				                                    Listar
				                                </a>
				                            </li>
				                            <li>
				                                <a href="ponto.php?mode=add">
				                                    Cadastrar
				                                </a>
				                            </li>
				                            <li>
				                                <a href="selecionar_ponto.php">
				                                    Associar Equipamentos
				                                </a>
				                            </li>				                            
				                        </ul>

				                    </li>				                    
									<?php } ?>
									
									<?php if (in_array($_SESSION['role_id'], array(ADMINISTRADOR,USUARIO))) { ?>
									<li class="nav-parent nav-expanded">
				                        <a href="#">
				                            <i class="fa fa-rss" aria-hidden="true"></i>
				                            <span>Feeds</span>
				                        </a>
				                        <ul class="nav nav-children">
				                            <li>
				                                <a href="rss_curitibacult.php?mode=list">
				                                    Curitiba Cult (Geral)
				                                </a>
				                            </li>
											<li>
				                                <a href="rss_curitibalocal.php?mode=list">
				                                    Curitiba Cult (Local)
				                                </a>
				                            </li>
											    <li>
				                                <a href="rss_cruzeiro_sorocaba.php?mode=list">
				                                    Cruzeiro do Sul - Sorocaba
				                                </a>
				                            </li>
											<li>
				                                <a href="rss_cruzeiro_geral.php?mode=list">
				                                    Cruzeiro do Sul - Geral
				                                </a>
				                            </li>
											<li>
				                                <a href="rss_megacurioso.php?mode=list">
				                                    Megacurioso
				                                </a>
				                            </li>				                            
											<li>
				                                <a href="rss_minhaserie.php?mode=list">
				                                    Minha Série
				                                </a>
				                            </li>		
											<li>
				                                <a href="rss_tecmundo.php?mode=list">
				                                    Tecmundo
				                                </a>
				                            </li>
											<li>
				                                <a href="rss_thebrief.php?mode=list">
				                                    TheBrief
				                                </a>
				                            </li>											
				                        	<li>
				                                <a href="rss_voxel.php?mode=list">
				                                    Voxel
				                                </a>
				                            </li>
				                        </ul>
				                    </li>
									<?php } ?>
									
									<?php if (in_array($_SESSION['role_id'], array(ADMINISTRADOR,USUARIO))) { ?>
									<li class="nav-parent">
				                        <a href="#">
				                            <i class="fa fa-rss" aria-hidden="true"></i>
				                            <span>Feeds v.2</span>
				                        </a>
				                        <ul class="nav nav-children">
				                            <li>
				                                <a href="add-feed.php?mode=add">
				                                    Adicionar Novo
				                                </a>
				                            </li>				                            											
				                        </ul>
				                    </li>
									<?php } ?>
									
				                    <li class="nav-parent">
				                        <a href="#">
				                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
				                            <span>Ocorrências</span>
				                        </a>
				                        <ul class="nav nav-children">
				                            <li>
				                                <a href="ocorrencias.php?mode=list">
				                                    Listar
				                                </a>
				                            </li>
				                            <?php if (in_array($_SESSION['role_id'], array(ADMINISTRADOR, TECNICO))) { ?>
				                            <li>
				                                <a href="ocorrencias.php?mode=add">
				                                    Cadastrar
				                                </a>
				                            </li>				                            
				                            <?php } ?>
				                        </ul>

				                    </li>
				                    <li>
				                        <a href="./logout.php">
				                            <i class="fa fa-times-circle-o" aria-hidden="true"></i>
				                            <span>Sair</span>
				                        </a>                        
				                    </li>				                    				                    
				                </ul>
				            </nav>
				
				            <hr class="separator" />
								            
				        </div>
				
				        <script>
				            // Maintain Scroll Position
				            if (typeof localStorage !== 'undefined') {
				                if (localStorage.getItem('sidebar-left-position') !== null) {
				                    var initialPosition = localStorage.getItem('sidebar-left-position'),
				                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
				                    
				                    sidebarLeft.scrollTop = initialPosition;
				                }
				            }
				        </script>
				        
				
				    </div>
				
				</aside>
				<!-- end: sidebar -->