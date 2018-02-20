<?php
/**
 * PHP Module for Beaver Builder
 *
 * @package FT_BB_PHP
 */

/**
 * Define the module
 */
class FTBBPHP extends FLBuilderModule {

	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'          => __( 'PHP Code', 'ft-bb-php' ),
			'description'   => __( 'Add PHP code to the page.', 'ft-bb-php' ),
			'category'		=> __( 'Advanced Modules', 'ft-bb-php' ),
			'group'			=> __( 'FireTree Design', 'ft-bb-php' ),
			'icon'			=> 'editor-code.svg',
			'dir'           => FT_BB_PHP_DIR . 'ft-bb-php/',
			'url'           => FT_BB_PHP_URL . 'ft-bb-php/',
		) );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		if ( FLBuilderModel::is_builder_active() ) {
	        $this->add_js( 'ace-mode-php', $this->url . 'js/ace/mode-php.js', array( 'ace' ), '', true );
	    }

	}

	/**
	 * Save the code to a file
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function ft_save_file() {

		if ( 0 === strlen( $this->node ) ) {
			return;
		}

		file_put_contents( $this->ft_get_file_path() , "<?php\nif ( ! defined( 'ABSPATH' ) ) {\n	exit;\n}\n?>\n\n<?php\n" . $this->settings->code );

	}

	/**
	 * Get the file path
	 *
	 * @since 1.0
	 *
	 * @return string File path
	 */
	public function ft_get_file_path() {

		$upload_dir = wp_upload_dir();
		return $upload_dir['basedir'] . '/bb-plugin/cache/' . $this->node . '-code.php';

	}

	/**
	 * Save the code to a temp file
	 *
	 * @since 1.0
	 *
	 * @param  array $settings Settings from the module.
	 *
	 * @return array           Settings from the module.
	 */
	public function update( $settings ) {

		if ( empty( $settings->code ) ) {
			return $settings;
		}

		$this->ft_save_file();

		return $settings;
	}

	/**
	 * Runs when the module is removed
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function remove() {

		if ( 0 === strlen( $this->node ) ) {
			return;
		}

		$upload_dir = wp_upload_dir();
		$file_path = $upload_dir['basedir'] . '/bb-plugin/cache/' . $this->node . '-code.php';

		unlink( $file_path );

	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('FTBBPHP', array(
	'code'       => array(
		'title'         => __( 'PHP Code', 'ft-bb-php' ),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'code'          => array(
						// 'type'          => 'ft-bb-php',
						'type' => 'code',
						'mode' => 'html',
						'default'		=> '',
						'label'         => '',
						'rows'          => '18',
						'preview'       => array(
							'type'     => 'none',
						),
					),
				),
			),
		),
	),
));

/**
 * [bt_php_field description]
 *
 * @since
 *
 * @param  string $name     Name.
 * @param  string $value    Value.
 * @param  array  $field    Field.
 * @param  array  $settings Settings.
 *
 * @return void
 */
function ft_bb_php_field( $name, $value, $field, $settings ) {
	?>
	<div class="fl-code-field">
		<div style="text-align: center;">
			<strong style="color: red;">Warning:</strong> Invalid code can break your site. Use carefully.
		</div>
		<?php $editor_id = 'ftbbphp' . time() . '_' . $name; ?>
		<textarea id="<?php echo esc_attr( $editor_id ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php echo ( isset( $field['class'] ) ) ? ' class="' . esc_attr( $field['class'] ) . '"' : ''; ?><?php echo ( isset( $field['rows'] ) ) ? ' rows="' . esc_attr( $field['rows'] ) . '"' : ''; ?>>
<?php echo esc_html( $value ); ?>
		</textarea>
		<script>

		jQuery(function(){

			var workerPathBackup = require("ace/config").get("workerPath");
			var workerPath = "<?php echo esc_html( FT_BB_PHP_URL ) . 'ft-bb-php/js/ace/'; ?>";
			require("ace/config").set("workerPath", workerPath);

			var textarea = jQuery('#<?php echo esc_attr( $editor_id ); ?>'),
				editDiv  = jQuery('<div>', {
					position:   'absolute',
					height:     parseInt(textarea.attr('rows'), 10) * 20
				}),
				editor = null;

			editDiv.insertBefore(textarea);
			textarea.css('display', 'none');
			ace.require('ace/ext/language_tools');
			editor = ace.edit(editDiv[0]);
			editor.$blockScrolling = Infinity;
			editor.getSession().setValue(textarea.val());
			editor.getSession().setMode('ace/mode/php');

			editor.setOptions({
				enableBasicAutocompletion: true,
				enableLiveAutocompletion: true,
				enableSnippets: false
			});

			editor.getSession().on('change', function(e) {
				textarea.val(editor.getSession().getValue()).trigger('change');
			});

			textarea.closest( '.fl-field' ).data( 'editor', editor );

			require("ace/config").set("workerPath", workerPathBackup);
		});

		</script>
		<style>
		.ace_scrollbar-inner {
			background-color: white;
			opacity: 0.01;
		}
		.fl-builder-ft-bb-php-settings #fl-builder-settings-section-general {
			margin-bottom: 0;
		}
		.fl-builder-ft-bb-php-settings #fl-builder-settings-section-general td {
			padding: 0;
		}
		</style>
	</div>
	<?php
}
add_action( 'fl_builder_control_ft-bb-php', 'ft_bb_php_field', 1, 4 );
