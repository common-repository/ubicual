<?php
/**
* Plugin Name: Ubicual
* Plugin URI: https://ubicual.com
* Description: Este Plugin permite incrustar formularios de landing pages en su web.
* Version: 2.4
* Author: Ubicual
* Author URI: http://kinetica.mobi
* License: GPL2
*/

class wp_ubicualc extends WP_Widget {




	// constructor
	// function wp_ubicual() {
	// 	parent::WP_Widget(false, $name = __('Ubicual', 'wp_widget_plugin') );
	// }

	public function __construct() {
			 //$this->WP_Widget( 'dokan-category-menu', 'Dokan: Product Category', $widget_ops );
			 $widget_ops = array( 'classname' => 'ubicual-widget', 'description' => 'Añade un widget para la recogida de datos desde ubicual.com' );
			 parent::__construct('ubicual', 'Añadir Captura ubicual.com', $widget_ops  );
	 }


	// widget form creation
	function form($instance) {

		// Check values
		if( $instance) {
			$title = esc_attr($instance['title']);
			$text = esc_attr($instance['text']);
			$btntext = esc_attr($instance['btntext']);
			$api_token = esc_attr($instance['api_token']);
			$landing_id = esc_attr($instance['landing_id']);
			$cssclass = esc_attr($instance['cssclass']);
			$showlabels = esc_attr($instance['showlabels']);
		} else {
			$title = '';
			$text = '';
			$btntext = '';
			$api_token = '';
			$landing_id = '';
			$cssclass = '';
			$showlabels = false;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Título', 'wp_widget_plugin'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Texto:', 'wp_widget_plugin'); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('btntext'); ?>"><?php _e('Botón:', 'wp_widget_plugin'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('btntext'); ?>" name="<?php echo $this->get_field_name('btntext'); ?>" type="text" value="<?php echo $btntext; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('api_token'); ?>"><?php _e('API Token', 'wp_widget_plugin'); ?> <a href="https://www.ubicual.com/api" target="_blank">Obtener token</a></label>
			<input class="widefat" placeholder=""  id="<?php echo $this->get_field_id('api_token'); ?>" name="<?php echo $this->get_field_name('api_token'); ?>" type="text" value="<?php echo $api_token; ?>" placeholder="Copia y pega aquí tu token"/>
			<?php
			if($api_token==""){

			echo '<button type="button" name="button" onclick="loadLandings()" id="'.$this->get_field_id('btnload').'">Comprobar</button>';

			}
			?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('landings'); ?>"><?php _e('Landing Page', 'wp_widget_plugin'); ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name('landings'); ?>" id="<?php echo $this->get_field_id('landings'); ?>" readonly required>
				<option value="">Seleccione una opción</option>
			</select>
			<input type="hidden" name="<?php echo $this->get_field_name('landing_id'); ?>" id="<?php echo $this->get_field_id('landing_id'); ?>" value="<?php echo $landing_id;?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cssclass'); ?>"><?php _e('Clase CSS', 'wp_widget_plugin'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('cssclass'); ?>" name="<?php echo $this->get_field_name('cssclass'); ?>" type="text" value="<?php echo $cssclass; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('showlabels'); ?>"><?php _e('Mostrar Etiquetas', 'wp_widget_plugin'); ?></label>
			<input class="checkbox" type="checkbox" <?php checked($instance['showlabels'], 'on'); ?> id="<?php echo $this->get_field_id('showlabels'); ?>" name="<?php echo $this->get_field_name('showlabels'); ?>" />
		</p>
		<script>
		jQuery( document ).ready(function() {
			if(jQuery("#<?php echo $this->get_field_id('api_token'); ?>").val()!=""){
				loadLandings()
			}
			jQuery("#<?php echo $this->get_field_id('landings'); ?>").change(function() {
				jQuery("#<?php echo $this->get_field_id('landing_id'); ?>").val(jQuery("#<?php echo $this->get_field_id('landings'); ?>").val())
			});
		});
		function loadLandings(){
			var token=jQuery("#<?php echo $this->get_field_id('api_token'); ?>").val();
			if(token!=""){
				jQuery.ajax({
					type: "POST",
					// url: "http://landing.paywall.test/api/landings",
					url: "https://landing.ubicual.com/api/landings",
					data: { api_token: token },
					success: function(data){
						jQuery("#<?php echo $this->get_field_id('btnload'); ?>").hide();
						jQuery("#<?php echo $this->get_field_id('landings'); ?>").prop('readonly', false);
						jQuery("#<?php echo $this->get_field_id('api_token'); ?>").prop('readonly', true);
						jQuery.each(data, function(key, item) {
							if(item.type=="2"){
								if(item.id==jQuery("#<?php echo $this->get_field_id('landing_id'); ?>").val()){
									var option=jQuery("<option></option>").attr("value",item.id).text(item.name).attr("selected","selected");
								}else{
									var option=jQuery("<option></option>").attr("value",item.id).text(item.name);
								}
								jQuery('#<?php echo $this->get_field_id('landings'); ?>').append(option);
							}
						});
					},
					error: function(data){
						alert("Se ha producido un error de acceso. Compruebe que su token es válido");
					},
					dataType: "json"
				});
			}else {
				alert("Por favor, copie y pegue el token obtenido en ubicual.com");
			}
		}

		</script>

		<?php
	}

	// update widget
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Fields
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = strip_tags($new_instance['text']);
		$instance['btntext'] = strip_tags($new_instance['btntext']);
		$instance['api_token'] = strip_tags($new_instance['api_token']);
		$instance['landing_id'] = strip_tags($new_instance['landing_id']);
		$instance['cssclass'] = strip_tags($new_instance['cssclass']);
		$instance['showlabels'] = $new_instance['showlabels'];
		return $instance;
	}

	// display widget

	function widget($args, $instance) {
		extract( $args );

		/** Proper way to enqueue scripts and styles */
		wp_enqueue_style('styleub', plugins_url( 'css/style.css', __FILE__));
		wp_enqueue_script( 'scriptub', plugins_url( 'js/script.js', __FILE__), array( 'jquery' ));

		// these are the widget options
		$title = apply_filters('widget_title', $instance['title']);
		$text = $instance['text'];
		$btntext = $instance['btntext'];
		$api_token = $instance['api_token'];
		$landing_id = $instance['landing_id'];
		$cssclass = $instance['cssclass'];
		$showlabels = $instance['showlabels'] ? 'true' : 'false';

		echo $before_widget;
		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box">';

		// Check if title is set
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		// Check if text is set
		if( $text ) {
			echo '<p class="wp_widget_plugin_text">'.$text.'</p>';

		}
		$url="https://landing.ubicual.com/api/landing/".$landing_id;
		$post_url="https://suscribers.ubicual.com/api/addcontact";
		if( $url ) {

			echo '<form action="'.$post_url.'" id="ubicualform" class="ubicualform" method="post" accept-charset="utf-8">';

			echo '<input type="hidden" name="_method" value="POST" />';
			$response = wp_remote_post(
            $url,
            array(
                'body' => array(
                    'api_token'   => $api_token
                )
            )
        );
			$d = json_decode($response['body'],true);

			if(!empty($d)){
				echo '<input type="hidden" name="user_id" value="'.$d['user_id'].'" />';
				echo '<input type="hidden" name="source" value="'.$d['id'].'" />';
				echo '<input type="hidden" name="type" value="2" />';
				echo '<input type="hidden" name="list_id[]" value="'.$d['list_id'].'" id="list_id">';

				foreach ($d['lists'] as $list) {
					echo '<input type="hidden" name="list_id[]" value="'.$list.'" id="list_id">';
				}
				$data=unserialize($d['formfields']);
				foreach($data as $field => $options){
					if($options['option'] != 0){
						echo "<div class='ubicualwrap form-group ".$cssclass."'>";
						if($showlabels == "true"){
							echo "<label>". $options['name']."</label>";
						}
						if($options['option'] == 2){
							echo "<input class='form-control' type='text' name='".$field."' id='".$field."' placeholder='".$options['name']."' required />";

						}	elseif($options['option'] == 1){
							echo "<input class='form-control' type='text' name='".$field."' id='".$field."' placeholder='".$options['name']."' />";

						}
						echo "</div>";
					}
				}

				$legalurl=$d['user']['policy_link'];
				if( $legalurl ) {
					echo '<input type="checkbox" name="terms" value="1" id="terms" /> Acepto las <a href="'.$legalurl.'" target="_blank">condiciones legales</a></label>';
				}
				else{
					echo '<input type="checkbox" name="terms" value="1" id="terms" /> Acepto las condiciones legales</label>';
				}
				echo '<br>';
				echo '<input class="btn btn-default" type="button" onclick="sb();" value="'.$btntext.'" />';
			}



			echo '</form>';
			echo '<div id="ubmsg"></div>';
			echo '</div>';
		}



		echo $after_widget;


	}
}

// register widget

add_action( 'widgets_init', function(){
	register_widget( 'wp_ubicualc' );
});
?>
