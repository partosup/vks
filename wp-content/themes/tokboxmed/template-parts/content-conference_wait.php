<!--db 
status_tokbox_post = 0 -> no conference crieted;
status_tokbox_post = 1 -> запрос на подключение к конференции;
status_tokbox_post = 2 -> запрос был отклонен админитсратором;-->
<link rel='stylesheet'  href='<?php echo(get_template_directory_uri()."/tokbox/css/load-page.css") ?>' type='text/css' media='all' />
	<div class="hwait-group">
		<?php

		$resultRow = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."tokbox");
		foreach( $resultRow as $row) {
			$sessionTok = $row->sessionTok;
		}
		if(empty($sessionTok)){//нет конференции
			echo ("<h1>На данный момент конференции нет...<br/>Попробуйте зайти позже</h1>");
		}else{//конференция создана
			if ($status_tokbox_post == 2){

				echo ("<h2>Ваш запрос был отклонен</h2>");
				echo("<a href=".home_url().">На главную</a></br>");
				echo("<button onclick='sendrequest(".get_current_user_id().")'>Отправить запрос повторно</button>");
				echo do_shortcode("[wpcrl_login_form]"); 
			}else{
			if ($status_tokbox_post !== 1){
					$user_email_box = $current_user->user_email;
					$prefix = $wpdb->prefix.'users';
					$wpdb->update(''.$prefix.'',
									array(status_tokbox_post=>'1'),
									array(user_email=>''.$user_email_box.'')
								);
				}
			
				echo ("<h1>Ждем одобрения администратора...</h1>");
			}
		}
		?>

	</div>

<div class="cssload-loader">
	<div class="cssload-dot"></div>
	<div class="cssload-dot"></div>
	<div class="cssload-dot"></div>
</div>

<?php
	if ($status_tokbox_post != 2 || empty($sessionTok)){?>
		<script>
			setTimeout(function(){
				location.reload();
			},5000);
		</script>		    
<?	}?>
<script>
	function sendrequest(IdUsert){
		$.ajax({
			url: "/wp-content/themes/tokboxmed/tokbox/ajaxUsersConnect.php",
			method: "POST",
			data: {resetSend : true, id : IdUsert },
			success: function( data ) {		
				location.reload();
			}
		});	
	}
</script>