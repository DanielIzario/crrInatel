<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br" dir="ltr">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" >
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="CRR" />
		<meta name="description" content="CRR" />
		<meta name="author" content="Daniel Izario" />
		<title>Maps - CRR</title>
		<link rel="icon" href="favicon.ico" type="image/x-icon"/>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>	
		<link href="bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="template.css" rel="stylesheet" type="text/css" />
		<link href="shortcodes.css" rel="stylesheet" type="text/css" />
		<script src = "jquery-3.0.0.js"></script>
		<link type = "text/css" rel = "stylesheet" href = "stylesheet.css"/>
		<script src = "leaflet.js" type = "text/javascript"></script>
		<link rel = "stylesheet" href = "leaflet.css" type = "text/css" >
		<script src = "easybutton.js" type = "text/javascript"></script>
		<link rel = "stylesheet" href = "easybutton.css" type = "text/css" >
		<script src = "leaflet.fullscreen.min.js" type = "text/javascript"></script>
		<link rel = "stylesheet" href = "leaflet.fullscreen.css" type = "text/css" >
		<script src="jquery.min.js" type="text/javascript"></script>
		<script src="bootstrap.min.js" type="text/javascript"></script>
		<script src="shortcodes.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" media="all" href="custom_geral.css" />
		<link rel="stylesheet" type="text/css" media="all" href="fonts.css" />
	</head>
	<body class="site com-content view-article no-layout no-task itemid-526 pt-br ltr  sticky-header layout-fluid" id = "body" onload = "onLoad()">
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
						<center>
							<h3 itemprop="name"> Escolas Urbanas por Município </h3>
						</center>
						<div id = "outer">
							<div id = "map" class = "mapViewer"></div>
						</div>

						<div id = "load" class = "load">
							<div id = "loadInner" class = "loadInner">
								<p>Aguarde, mapa carregando!</p>	
								<div id = "loadCircle"></div>
							</div>
						</div>

						<script type = "text/javascript">
							// Variáveis Globais
							// Objeto GeoJson com informações
							var geojsonObject = null, geoObject = null, gjson = null;
							// Variável que reberá o mapa
							var map = null;
							// Posição do centro
							var lat = -15, lon = -55, zoom = 4;
							// Variáveis do marcador e popup
							var circle = null, popup = null;
							// Adiciona Campo para mensagem
							var info = L.control();

							// Função chamada ao carregar a pagina
							function onLoad(){
								// Verifica o navegador do usuário
								verifyBrowser();
								// Carrega o GeoJSON com as informações
								getJSON();
							}
							// Função para abrir o geojson via ajax ao carregar a pagina
							function getJSON(){
								// Arquivo GEOJson a ser aberto
								var url = "informacoes_geojson.geojson";
								// Abre o GeoJson com os dados
								$.getJSON(url, function(json) {
									// Oculta a div com loading
									$('.loadInner').hide();
									// salva o arquivo GEOJson aberto
									gjson = json;
									// Cria o mapa
									createMap();
									// Cria a camada principal a partir do GEOJson
									geojsonObject = L.geoJson(json, {style: style, onEachFeature: onEachFeature});
									// Adiciona a camada principal no mapa
									geojsonObject.addTo(map);
								});
							}
							function verifyBrowser(){

								// Se for Internet Explore ou Edge  
								// Sugere para usuário utilizar outro navegador
								if(L.Browser.ie || L.Browser.edge){
									alert( "Para uma melhor experiência, recomendamos que você utilize outro navegador. " );
								}
							}
							// Função para criar o mapa	
							function createMap(){
								// Cria um novo mapa
								map = L.map('map', {fullscreenControl: true }).setView([-15, -55], 4);
								
								// Seleciona o basemap
								L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
								   attribution: '&copy; <a target="_blank" href="http://www.inatel.br/crr/">CRR</a> Inatel',
										minZoom: 4, maxZoom: 13, unloadInvisibleTiles: true, updateWhenIdle: true
								}).addTo(map);


								info.onAdd = function (map) {
									this._div = L.DomUtil.create('div', 'info'); // Create a div with a class "info"
									this.update();
									return this._div;
								};

								// Method that we will use to update the control based on feature properties passed
								info.update = function (props) {

									// Atualiza a div com o dado do município que o mouse esta sobre
									this._div.innerHTML = '<h4>Escolas Urbanas por Município &nbsp;&nbsp;&nbsp;</h4>' + (props  ?
										'<b>' + '<i class="info_legenda" style="background:' + getColor(props.esc_urb) + '"></i>' + 
											props.nome + ', ' +  props.uf + '</b><br />' + props.esc_urb + ' escolas</sup>'  : ' ');
								};

								// Adiciona as informações feitas acima ao mapa
								info.addTo(map);

								// Variável que receberá a legenda do mapa
								var legend = L.control({position: 'bottomright'});

								// Cria a legenda
								legend.onAdd = function (map) {

									var div = L.DomUtil.create('div', 'legend'),
									grades = [0, 25, 50, 100, 200, 400, 800];

									// Checkbox para habilitar o filtro
									div.innerHTML += '<input id="checkFilter" type="checkbox" /> &nbsp; Filtrar <br> ';

									// Loop through our population intervals and generate a label with a colored square for each interval
									for (var i = 0; i < grades.length; i++) {
										div.innerHTML +=
											'<i class="legenda" style="background:' + getColor(grades[i]) + '"/></i><input id="check' + i + 
											'" type="radio" disabled/> ' +
											grades[i] + (grades[i + 1] ? ' &ndash; ' + grades[i + 1] + '<br>' : ' +');
									}

									return div;
								};

								// Adiciona a legenda (criada acima) ao mapa
								legend.addTo(map);

								// Adiciona ação dos checkboxes (Listener do click)
								document.getElementById("check0").addEventListener("click", check0Clicked, true);
								document.getElementById("check1").addEventListener("click", check1Clicked, true);
								document.getElementById("check2").addEventListener("click", check2Clicked, true);
								document.getElementById("check3").addEventListener("click", check3Clicked, true);
								document.getElementById("check4").addEventListener("click", check4Clicked, true);
								document.getElementById("check5").addEventListener("click", check5Clicked, true);
								document.getElementById("check6").addEventListener("click", check6Clicked, true);

								// Adiciona o eventro de click
								document.getElementById("checkFilter").addEventListener("click", checkFilter, true);
							}
							// Funcao para diferenciar o estilo de cada feicao (padrão)
							function getColor(p) {
								return p >= 800 ? '#1a9850' :
									   p >= 400 ? '#a6d96a' :
									   p >= 200 ? '#d9ef8b' :
									   p >= 100 ? '#fee08b' :
									   p >= 50 ? '#fdae61' :
									   p >= 25 ? '#f46d43' :
													'#d73027';
							}
							// Funcao para aplicar o estilo padrão
							function style(feature) {
								return {
									fillColor: getColor(feature.properties.esc_urb),
									weight: 1,
									opacity: 0.98,
									color: 'grey',
									dashArray: '',
									fillOpacity: 0.8
								};
							}

							// Aplica o estilo ao passar o mouse
							function highlightFeature(e) {
								var layer = e.target;

								// Estilo ao passar o mouse
								layer.setStyle({
									weight: 4,
									color: '#4D4D4D',
									dashArray: '',
									fillOpacity: 0.5
								});

								// Coloca a camada na frente das outras (por cima)
								if(!L.Browser.ie && !L.Browser.edge){ layer.bringToFront(); }

								// Coloca o circulo na frente da camada
								if( circle != null ){ circle.bringToFront(); }

								info.update(layer.feature.properties);
							}
							// Limpa a formatação ao retirar o mouse
							function resetHighlight(e) {
								geojsonObject.resetStyle(e.target);
								info.update();
							}
							// Da zoom para feição ao clicar
							function zoomToFeature(e) {
								// Ajusta o zoom para o tamanho do município
								map.fitBounds(e.target.getBounds());

								// Se possuir algum outro circulo o remove
								if( circle!=null ){ map.removeLayer(circle); }

								// Desenha um circulo na posição do click
								circle = L.circle(e.latlng, {
									color: '#000000',
									fillColor: '#FFFFFF',
									fillOpacity: 0.4,
									radius: 1200
								}).addTo(map);

								var prop = e.target.feature.properties;

								popup = L.popup()
									.setLatLng(e.latlng)
									.setContent('<p>' + prop.nome + ', ' + prop.uf  + '<br/>' + prop.esc_urb + '</p>')
									.openOn(map);
							}

							// Aplica as funcionalidades a cada feição
							function onEachFeature(feature, layer) {
								layer.on({
									mouseover: highlightFeature,
									mouseout: resetHighlight,
									click: zoomToFeature
								});
							}
							// Função para limpar os marcadores
							function removeMarkers(){
								// Se tem algum circulo desenhado, é apagado
								if( circle != null ) {
									map.removeLayer(circle); // apaga o circulo
									circle=null;
								}

								// Se tem algum popup desenhado, é apagado 
								if( popup != null ){
									map.removeLayer(popup); // apaga o popup
									popup=null;
								}
							}

							// Funções para aplicar os estilos dos filtros
							// Uma função para cada intervalo da legenda
							function style0(feature) {
								var escURB = feature.properties.esc_urb;

								if( escURB >= 0 && escURB < 25 ){
									return {
										fillColor: '#d73027',
										weight: 1,
										opacity: 0.98,
										color: 'grey',
										dashArray: '',
										fillOpacity: 0.9
									};
								}else{
									return transparentColor();
								}  
							}
							function style1(feature) {
								var escURB = feature.properties.esc_urb;

								if( escURB >= 25 && escURB < 50  ){
									return {
										fillColor: '#f46d43',
										weight: 1,
										opacity: 0.98,
										color: 'grey',
										dashArray: '',
										fillOpacity: 0.9
									};
								}else{
									return transparentColor();
								}  
							}
							function style2(feature) {
								var escURB = feature.properties.esc_urb;

								if( escURB >= 50 && escURB < 100 ){
									return {
										fillColor: '#fdae61',
										weight: 1,
										opacity: 0.98,
										color: 'grey',
										dashArray: '',
										fillOpacity: 0.9
									};
								}else{
									return transparentColor();
								}  
							}
							function style3(feature) {
								var escURB = feature.properties.esc_urb;

								if( escURB >= 100 && escURB < 200 ){
									return {
										fillColor: '#fee08b',
										weight: 1,
										opacity: 0.98,
										color: 'grey',
										dashArray: '',
										fillOpacity: 0.9
									};
								}else{
									return transparentColor();
								}  
							}
							function style4(feature) {
								var escURB = feature.properties.esc_urb;

								if( escURB >= 200 && escURB < 400 ){
									return {
										fillColor: '#d9ef8b',
										weight: 1,
										opacity: 0.98,
										color: 'grey',
										dashArray: '',
										fillOpacity: 0.9
									};
								}else{
									return transparentColor();
								}  
							}
							function style5(feature) {
								var escURB = feature.properties.esc_urb;

								if( escURB >= 400 && escURB < 800 ){
									return {
										fillColor: '#a6d96a',
										weight: 1,
										opacity: 0.98,
										color: 'grey',
										dashArray: '',
										fillOpacity: 0.9
									};
								}else{
									return transparentColor();
								}  
							}
							function style6(feature) {
								var escURB = feature.properties.esc_urb;

								if( escURB >= 800 ){
									return {
										fillColor: '#1a9850',
										weight: 1,
										opacity: 0.98,
										color: 'grey',
										dashArray: '',
										fillOpacity: 0.9
									};
								}else{
									return transparentColor();
								}  
							}
							// Função que retorna cor transparente
							function transparentColor(){
								return {
									fillColor: '#FFFFFF',
									weight: 1,
									opacity: 0.98,
									color: 'grey',
									dashArray: '',
									fillOpacity: 0
								};
							}
							// Funções para verificar qual botão foi clicado e adiciona a camada no mapa
							function check0Clicked(){
								removeFilter();

								geoObject = L.geoJson(gjson, {style: style0});
								//, onEachFeature: onEachFeature

								geoObject.addTo(map);

								// Desmarca os outros radio buttons
								$('#check1').prop("checked", false);
								$('#check2').prop("checked", false);
								$('#check3').prop("checked", false);
								$('#check4').prop("checked", false);
								$('#check5').prop("checked", false);
								$('#check6').prop("checked", false);
							}
							function check1Clicked(){
								removeFilter();

								geoObject = L.geoJson(gjson, {style: style1});

								geoObject.addTo(map);

								$('#check0').prop("checked", false);
								$('#check2').prop("checked", false);
								$('#check3').prop("checked", false);
								$('#check4').prop("checked", false);
								$('#check5').prop("checked", false);
								$('#check6').prop("checked", false);
								$('#check7').prop("checked", false);
							}
							function check2Clicked(){
								removeFilter();

								geoObject = L.geoJson(gjson, {style: style2});

								geoObject.addTo(map);

								$('#check0').prop("checked", false);
								$('#check1').prop("checked", false);
								$('#check3').prop("checked", false);
								$('#check4').prop("checked", false);
								$('#check5').prop("checked", false);
								$('#check6').prop("checked", false);
							}
							function check3Clicked(){
								removeFilter();

								geoObject = L.geoJson(gjson, {style: style3});

								geoObject.addTo(map);

								$('#check0').prop("checked", false);
								$('#check1').prop("checked", false);
								$('#check2').prop("checked", false);
								$('#check4').prop("checked", false);
								$('#check5').prop("checked", false);
								$('#check6').prop("checked", false);
							}
							function check4Clicked(){
								removeFilter();

								geoObject = L.geoJson(gjson, {style: style4});

								geoObject.addTo(map);

								$('#check0').prop("checked", false);
								$('#check1').prop("checked", false);
								$('#check2').prop("checked", false);
								$('#check3').prop("checked", false);
								$('#check5').prop("checked", false);
								$('#check6').prop("checked", false);
							}
							function check5Clicked(){
								removeFilter();

								geoObject = L.geoJson(gjson, {style: style5});

								geoObject.addTo(map);

								$('#check0').prop("checked", false);
								$('#check1').prop("checked", false);
								$('#check2').prop("checked", false);
								$('#check3').prop("checked", false);
								$('#check4').prop("checked", false);
								$('#check6').prop("checked", false);
							}
							function check6Clicked(){
								removeFilter();

								geoObject = L.geoJson(gjson, {style: style6});

								geoObject.addTo(map);

								$('#check0').prop("checked", false);
								$('#check1').prop("checked", false);
								$('#check2').prop("checked", false);
								$('#check3').prop("checked", false);
								$('#check4').prop("checked", false);
								$('#check5').prop("checked", false);
							}
							// Remove todas as camadas de filtros
							function removeFilter(){
								if( geoObject != null ){
									map.removeLayer(geoObject);
								}
							}
							// Ação do Checkbox do Filtro
							function checkFilter() {
								// Habilita ou desabilita os checkboxes dos filtros
								if( this.checked ){
									$('#check0').removeAttr("disabled");
									$('#check1').removeAttr("disabled");
									$('#check2').removeAttr("disabled");
									$('#check3').removeAttr("disabled");
									$('#check4').removeAttr("disabled");
									$('#check5').removeAttr("disabled");
									$('#check6').removeAttr("disabled");

									// Remove a camada principal do mapa
									map.removeLayer(geojsonObject);

									// button 0 clicado por padrão
									$('#check0').prop("checked", true);
									check0Clicked();

								}else{
									// Desabilita checkboxes
									$('#check0').attr("disabled", true);
									$('#check1').attr("disabled", true);
									$('#check2').attr("disabled", true);
									$('#check3').attr("disabled", true);
									$('#check4').attr("disabled", true);
									$('#check5').attr("disabled", true);
									$('#check6').attr("disabled", true);

									// Desmarca checkboxes
									$('#check0').prop("checked", false);
									$('#check1').prop("checked", false);
									$('#check2').prop("checked", false);
									$('#check3').prop("checked", false);
									$('#check4').prop("checked", false);
									$('#check5').prop("checked", false);
									$('#check6').prop("checked", false);

									// Remove todas as camadas de filtros
									removeFilter();

									// Adiciona a camada principal
									geojsonObject.addTo(map);
								}
							}			
						</script>
						<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
						<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
						<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
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