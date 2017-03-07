 <div class="row">
  	<div class="col-md-12">
  		<?php	echo do_shortcode("[wpcrl_login_form]"); ?>
 	</div>
</div> 	
 <div class="row">
  	<div class="col-md-12">
		<?php if ( is_super_admin() ) { ?>
			<button type="button" id="createConf" class="btn btn-success">Создать Конференцию</button>
			<button type="button" id="destroyConf" class="btn btn-danger">Закончить Конференцию</button>
			<div class="row">
				<div id="circle-checkbox" class="muteallaudio col-md-2" >
				    <span>Микрофоны у всех:</span>
					<input onclick="muteallaudio();" class="circle-nicelabel" data-nicelabel='{"position_class": "circle-checkbox"}' checked type="checkbox" />
				</div>
				<div id="circle-checkbox" class="col-md-2" >
				    <span>Запись:</span>
					<input onclick="start_archive();" class="circle-nicelabel" data-nicelabel='{"position_class": "circle-checkbox"}' type="checkbox" />
				</div>
			</div>
		<? }?>	
			
	</div>
</div>
 <div class="row">
  	<div class="col-md-4">
  		<div id="WindowModerator"></div>
    <?php if ( is_super_admin() ) { ?>
		<p>Новое подключение</p>
  		<ul class="list-of-users"></ul>
    <?}?>
	</div>
	<div class="col-md-8">
  		<div id="WindowPublisher"></div>
	</div>
</div> 



<script>
$(function() {
	$('#circle-checkbox > input').nicelabel();
});
</script>