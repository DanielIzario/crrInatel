<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br" dir="ltr">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" >
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="CRR" />
		<meta name="description" content="CRR" />
		<meta name="author" content="Daniel Izario" />
		<title>Tables - CRR</title>
		<link rel="icon" href="favicon.ico" type="image/x-icon"/>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
		<link href="bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="template.css" rel="stylesheet" type="text/css" />
		<link href="shortcodes.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			#sp-section-10{ padding:0px 0 40px 0; }
			#sp-bottom{ padding:60px 0px; }
			
			.wrap {
				width: 600px;
				border-bottom: 1px solid #eee;
			}

			.wrap table {
				width: 600px;
			}

			table{
				border-collapse: collapse;
				overflow-x: hidden;
			}

			table tr td {
				padding: 2px;
				border-bottom: 1px solid #eee;
				height: 25px;
				table-layout: fixed;
				border-collapse: collapse;
				word-wrap: break-word;
				overflow-x: hidden;
			}
		</style>
		<script src = "jquery-3.0.0.js"></script>
		<link type = "text/css" rel = "stylesheet" href = "stylesheet.css"/>
		<script src="jquery.min.js" type="text/javascript"></script>
		<script src="bootstrap.min.js" type="text/javascript"></script>
		<script src="shortcodes.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" media="all" href="custom_geral.css" />
		<link rel="stylesheet" type="text/css" media="all" href="fonts.css" />
	</head>
	<body class="site com-content view-article no-layout no-task itemid-526 pt-br ltr  sticky-header layout-fluid" id = "body">
		<div class="body-innerwrapper">
			<section id="sp-top-bar">
				<div class="container">
					<div class="row">
						<div id="sp-top1" class="col-xs-10 col-sm-102 col-md-12">
							<div class="sp-column ">
								<div class="sp-module ">
									<div class="sp-module-content">
										<div class="custom">
											<script type="text/javascript" src="jquery.maskedinput-1.1.4.pack.js"></script>
											<div class="MenuGeralInatel DX-nav">
												<div class="menuzoom">
													<div class="Logo-Inatel">
														<h1><a href="http://www.inatel.br/home/"><img src="logo.png" alt="Inatel" /></a></h1>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section id="sp-main-body">
				<div class="container">
					<div class="row">
						<div class = "divSearch">
							<h3 itemprop="name"> Escolas Urbanas por Município </h3>
							<center>
								<h5 itemprop="name"> Pesquisar: </h5>
								<input id = "inputSearch" maxlength = "54" type = "text" name = "text" size = "70" placeholder = "Digite sua Pesquisa!"><br>
								<button title = "Pesquisar" class = "button" id = "buttonSearch" type = "button" onclick = "btnSearch()">Pesquisar</button>&nbsp;&nbsp;<button title = "Todos os Resultados" class = "button" id = "buttonAll" type = "button" onclick = "connect()">Todos os Resultados</button>
							</center>
						</div>
						<div id = "div_table"></div>
						<script type="text/javascript">
							var flag = 1;
							var content = "";
							var _coluna = "ESC_URB"; 
							
							connect();
							
							function connect(){
								if( flag != 0 ){
									flag = 0;
									
									$.ajax({
										url:'banco_todos.php',    // função php que conecta com o banco
										method: "post",           // método usado para passar os parâmetros
										data: {coluna: _coluna }, // parâmetros passados para o php
										complete: function (response) {
											// Chama a função para construir a tabela passando os resultados
											fncMunicipios(response.responseText);
										},
										error: function () {
											// Caso ocorra algum erro na conexão
											console.log('Error');
										}
									});
								}
							}

							// Função chamada pelo retorno do php
							// Constroi a tabela com os resultados
							function fncMunicipios(response){

								var d = document.getElementById("wrap");
								var cont = document.body.contains(d);

								var div = document.getElementById("div_table");

								// verifica se tabela ja exista e limpa
								if( cont == false ){
									// div inner
									var wrap = document.createElement("div");
									wrap.setAttribute('class', 'wrap');
									wrap.setAttribute('id', 'wrap');
								}else{
									var wrap = document.getElementById("wrap");
								}
								
								wrap.innerHTML = "";
								div.innerHTML = "";

								var res = response.split("#");
								var sz = res.length;
								var i=0;
								var columns = 5;
								var qtd = (sz-1)/columns;

								// elemento para pular linha
								var br = document.createElement("p");

								// cria elementro center para centralizar a div
								var center = document.createElement("center");

								if( qtd>0 ){

									// div de fora
									var wrap = document.createElement("div");
									wrap.setAttribute('class', 'wrap');

									// div de dentro
									var inner = document.createElement("div");
									inner.setAttribute('class', 'inner');

									// table do cabeçalho
									var head = document.createElement("table");
									head.setAttribute('class', 'head');

									// table do conteudo
									var table = document.createElement("table");
									table.setAttribute('class', 'inner_table');

									// linha do cabeçalho
									var tr = document.createElement("tr");

									// conteudo do cabeçalho
									var td = document.createElement("td");
									td.appendChild(document.createTextNode( "Código IBGE" ));
									td.setAttribute( 'style', 'width:90px; vertical-align: middle; text-align: center;' );
									tr.appendChild(td);

									var td = document.createElement("td");
									td.appendChild(document.createTextNode( "Município" ));
									td.setAttribute( 'style', 'width:250px; vertical-align: middle; text-align: center;' );
									tr.appendChild(td);

									var td = document.createElement("td");
									td.appendChild(document.createTextNode( "Estado" ));
									td.setAttribute( 'style', 'width:90px; vertical-align: middle; text-align: center;' );
									tr.appendChild(td);

									var td = document.createElement("td");
									td.appendChild(document.createTextNode( "Região" ));
									td.setAttribute( 'style', 'width:90px; vertical-align: middle; text-align: center;' );
									tr.appendChild(td);
									
									var td = document.createElement("td");
									td.appendChild(document.createTextNode( "Escolas" ));
									td.setAttribute( 'style', 'width:90px; vertical-align: middle; text-align: center;' );
									tr.appendChild(td);

									// adiciona o cabeçalho na tabela head
									head.appendChild( tr );

									// adiciona a tabela head na div principal
									wrap.appendChild( head );

									// conteudo da tabela (2a table)
									while( i<(sz-2) ){

										//cria cada linha de conteudo da tabela
										var tr = document.createElement("tr");
										
										var td = document.createElement("td");
										td.appendChild(document.createTextNode( res[i] ));
										td.setAttribute( 'style', 'width:90px; vertical-align: middle; text-align: center;' );
										tr.appendChild(td);
										center.appendChild(tr);
										i++;

										var td = document.createElement("td");
										td.appendChild(document.createTextNode( res[i] ));
										td.setAttribute( 'style', 'width:250px; vertical-align: middle; text-align: center;' );
										tr.appendChild(td);
										center.appendChild(tr);
										i++;

										var td = document.createElement("td");
										td.appendChild(document.createTextNode( res[i] ));
										td.setAttribute( 'style', 'width:90px; vertical-align: middle; text-align: center;' );
										tr.appendChild(td);
										center.appendChild(tr);
										i++;

										var td = document.createElement("td");
										td.appendChild(document.createTextNode( res[i] ));
										td.setAttribute( 'style', 'width:90px; vertical-align: middle; text-align: center;' );
										tr.appendChild(td);
										center.appendChild(tr);
										i++;

										var td = document.createElement("td");
										td.appendChild(document.createTextNode( res[i] ));
										td.setAttribute( 'style', 'width:90px; vertical-align: middle; text-align: center;' );
										tr.appendChild(td);
										center.appendChild(tr);
										i++;
									
										// adiciona a linha na tabela
										table.appendChild(tr);
										center.appendChild(table);
									}

									// adiciona atable de conteudo na dive interna
									inner.appendChild( table );

									// adiociona na div principal
									wrap.appendChild( inner );

									// total de resultados
									wrap.appendChild(br);
									var p = document.createElement("p");
									p.setAttribute('id','results');
									p.appendChild( document.createTextNode("Total de resultados: " + qtd) );
									wrap.appendChild(p);

									// Data dos dados
									var p = document.createElement("p");
									p.appendChild( document.createTextNode("") );
									wrap.appendChild(p);
									wrap.appendChild(br); 
									wrap.appendChild(br);

									// centraliza
									center.appendChild( wrap );
									center.appendChild(br);

									// adiciona na div_table
									div.appendChild(center);
								}else{
									// se nenhum resultado foi retornado
									
									var p = document.createElement("p");

									p.appendChild( document.createTextNode("Nenhum resultado encontrado!") );

									center.appendChild( p );

									div.appendChild( center );
								}
							}

							function btnSearch(){

								var inputSearch = document.getElementById("inputSearch");

								if( flag!=1 || content!=inputSearch.value ){

									flag = 1;

									if( inputSearch.value != "" ){
										// atualiza o placeholder
										var msg = "Digite sua Pesquisa!";
										inputSearch.placeholder = msg;

										// elimina espaços antes e depois, se houver
										var value = (inputSearch.value).trim();
										var _from = "ESC_URB";

										$.ajax({
											url:'banco_busca.php',
											method: "post",
											data: {text: value, from: _from },
											complete: function (response) {
												fncMunicipios(response.responseText);
											},
											error: function () {
												alert('Error');
											}
										});
						
									}else{
										// Atualiza o placeholder se usuario não digitar nada
										var msg = "Digite algo para pesquisar!";
										inputSearch.placeholder = msg;
									}
								}
								// Atualiza content
								content = inputSearch.value;
							}
							// Script para pesquisar ao pressionar enter
							$(document).ready(function(){
								$('#inputSearch').keypress(function(e){
									if(e.keyCode==13)
										$('#buttonSearch').click();
								});
							});
						</script>
					</div>
				</div>
			</section>
			<section id="sp-bottom">
				<div class="container">
					<div class="row">
						<div id="sp-bottom1" class="col-sm-6 col-md-6">
							<div class="sp-column ">
								<div class="sp-module ">
									<div class="sp-module-content">
										<div class="custom"  >
											<p>
												<p class="left-center-rodape" style="margin-bottom:3px;"><a href="" class="icon-contato"><span style="font-size:20px;">@</span> Entre em contato</a></p>
												<p class="left-center-rodape"><strong class="SJ-FW-600">Inatel - Instituto Nacional de Telecomunicações</strong></p>
												<p class="left-center-rodape"><strong><i>Campus</i> em Santa Rita do Sapucaí - MG - Brasil</strong></p>
												<p class="left-center-rodape"><strong>Av. João de Camargo, 510 - Centro - 37540-000</strong></p>
												<p class="left-center-rodape"><strong><a href="tel:+55 35 3471.9200">+55&nbsp;(35) 3471 9200</a></strong></p>
												<script>
													jQuery(function($) {
														$("#telefone").mask("?(99) 999999999");
														$("#formadoAno").mask("?9999");
														$("#dddSocio").mask("?(99)");
														$("#celularSocio").mask("?999999999");
														$("#dddCelularSocio").mask("?(99)");
														$("#telefoneSocio").mask("?999999999");
														$("#cpfSocio").mask("?999.999.999-99");
														$("#dddSocio2").mask("?(99)");
														$("#telefoneSocio2").mask("?999999999");
														$("#dddCelularSocio2").mask("?(99)");
														$("#celularSocio2").mask("?999999999");
														$("#anoSocio2").mask("?9999");

														$("#dddSocio3").mask("?(99)");
														$("#telefoneSocio3").mask("?999999999");
														$("#dddCelularSocio3").mask("?(99)");
														$("#celularSocio3").mask("?999999999");
														$("#anoSocio3").mask("?9999");
														$("#dddSocio4").mask("?(99)");
														$("#telefoneSocio4").mask("?999999999");
														$("#dddCelularSocio4").mask("?(99)");
														$("#celularSocio4").mask("?999999999");
														$("#anoSocio4").mask("?9999");

														$("#dddSocio5").mask("?(99)");
														$("#telefoneSocio5").mask("?999999999");
														$("#dddCelularSocio5").mask("?(99)");
														$("#celularSocio5").mask("?999999999");
														$("#anoSocio5").mask("?9999");

														$("#dddSocio6").mask("?(99)");
														$("#telefoneSocio6").mask("?999999999");
														$("#dddCelularSocio6").mask("?(99)");
														$("#celularSocio6").mask("?999999999");
														$("#anoSocio6").mask("?9999");
														$("#hora").mask("?99:99");
														$("#horario").mask("?99:99");
														$("#horarioEnsaio").mask("?99:99");
														$("#horarioEnsaioFinal").mask("?99:99 às 99:99");

														$("#telefoneComercial").mask("?(99) 999999999");
														$("#telefoneParaRecados").mask("?(99) 999999999");
														$("#anoConclusao").mask("9999");
														$("#CNPJ").mask("?99.999.999/9999-99");
														$("#cnpj").mask("?99.999.999/9999-99");

														$("#Telefone").mask("?(99) 999999999");
														$(".telefone").mask("?(99) 999999999");
														$(".Telefone").mask("?(99) 999999999");
														$("#telResidencial").mask("?(99) 999999999");
														$("#telComercial").mask("?(99) 999999999");
														$("#celular").mask("?(99) 999999999");
														$("#Celular").mask("?(99) 999999999");
														$(".Celular").mask("?(99) 999999999");
														$(".celular").mask("?(99) 999999999");
														$("#telResidencialEvento").mask("?(99) 999999999");
														$("#telComercialEvento").mask("?(99) 999999999");
														$("#telefoneRecado").mask("?(99) 999999999");
														$("#telefoneResidencial").mask("?(99) 999999999");
														
														$("#celularEvento").mask("?(99) 999999999");

														$("#CPF").mask("999.999.999-99");
														$("#cpf").mask("999.999.999-99");
														$("#cpfEvento").mask("999.999.999-99");

														$("#data").mask("99/99/9999");
														$("#dataEvento").mask("99/99/9999");
														$("#dataEnsaio").mask("99/99/9999");
														$("#dataEnsaioFinal").mask("99/99/9999");
														$("#dataNascimento").mask("99/99/9999");
														$("#inicioDaDivulgacao").mask("99/99/9999");
														
													});

													function closers() {
														document.getElementById("closeded").innerHTML = '';
														return false;
													}
												</script>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="sp-bottom4" class="col-sm-6 col-md-6">
							<div class="sp-column ">
								<div class="sp-module ">
									<div class="sp-module-content">
										<div class="custom">
											<p>
												<p class="left-center-rodape SJ-ML-50-767-rodapez SJ-MT-3-30-rodape" style="margin-bottom:3px;"><strong>Escritório em São Paulo - SP - Brasil</strong></p>
												<p class="left-center-rodape SJ-ML-50-767-rodapez"><strong><i>WTC Tower</i>, 18º andar - Conjunto 1811/1812</strong></p>
												<p class="left-center-rodape SJ-ML-50-767-rodapez"><strong>Av. das Nações Unidas, 12.551 - Brooklin Novo - 04578-903</strong></p>
												<p class="left-center-rodape SJ-ML-50-767-rodapez"><strong><a href="tel:+55 11 3043.6015">+55&nbsp;(11) 3043 6015</a> | <a href="mailto:inatel.sp@inatel.br">inatel.sp@inatel.br</a></strong></p>
												<script type="text/javascript"> var google_conversion_id = 1069507166; var google_custom_params = window.google_tag_params; var google_remarketing_only = true; </script> 
												<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"> </script> 
												<noscript> 
													<div style="display:inline;"> 
														<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1069507166/?value=0&amp;guid=ON&amp;script=0"/> 
													</div> 
												</noscript> 
												<script>
													(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
													(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
													m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
													})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

													ga('create', 'UA-273753-1', 'auto');
													ga('send', 'pageview');
												</script>
												<script>
													!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
													n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
													n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
													t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
													document,'script','https://connect.facebook.net/en_US/fbevents.js');

													fbq('init', '1496383317283246');
													fbq('track', "PageView");
												</script>
												<noscript>
													<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1496383317283246&ev=PageView&noscript=1"/>
												</noscript>
												<script type="text/javascript" async src="https://d335luupugsy2.cloudfront.net/js/loader-scripts/1ffcc70b-c7e2-4965-8041-a850bcd8c5d4-loader.js" ></script>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<script type="text/javascript">
			function jSelectShortcode(text) {
				jQuery("#yt_shorcodes").removeClass("open");
				text = text.replace(/'/g, '"');
				if(document.getElementById('jform_articletext') != null) {
					jInsertEditorText(text, 'jform_articletext');
				}
				if(document.getElementById('jform_description') != null) {
					jInsertEditorText(text, 'jform_description');
				}
				if(document.getElementById('description') != null) {
					jInsertEditorText(text, 'description');
				}
				if(document.getElementById('text') != null) {
					jInsertEditorText(text, 'text');
				}
				if(document.getElementById('category_description') != null) {
					jInsertEditorText(text, 'category_description');
				}
				if(document.getElementById('product_desc') != null) {
					jInsertEditorText(text, 'product_desc');
				}
				if(document.getElementById('jform_misc') != null) {
					jInsertEditorText(text, 'jform_misc');
				}
				if(document.getElementById('write_content') != null) {
					jInsertEditorText(text, 'write_content');
				}
				if(document.getElementById('description1') != null) {
					jInsertEditorText(text, 'description1');
				}
				if(document.getElementById('jform_content') != null) {
					jInsertEditorText(text, 'jform_content');
				}
				SqueezeBox.close();
			}
		</script>
	</body>
</html>