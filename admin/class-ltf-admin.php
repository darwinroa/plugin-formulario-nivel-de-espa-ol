<?php
/**
 * La funcionalidad de administración del plugin.
 */
class LTF_Admin {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_plugin_admin_menu' ] );
	}

	/**
	 * Registra el menú en el panel de administración de WordPress
	 */
	public function add_plugin_admin_menu() {
		add_menu_page(
			'Test de Nivel',           // page_title
			'Test de Nivel',           // menu_title
			'manage_options',          // capability
			'level-test-form',         // menu_slug
			[ $this, 'display_plugin_setup_page' ], // callback
			'dashicons-clipboard',     // icon
			25                         // position
		);
	}

	/**
	 * Renderiza la interfaz de la página de administración
	 */
	public function display_plugin_setup_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'ltf_submissions';

		// Obtener todas las sumisiones
		$results = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY created_at DESC" );
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline">Resultados del Test de Nivel</h1>
			<p>A continuación se muestran los resultados enviados por los estudiantes a través del formulario público.</p>

			<table class="wp-list-table widefat fixed striped table-view-list">
				<thead>
					<tr>
						<th scope="col" class="manage-column column-primary">ID del Test</th>
						<th scope="col" class="manage-column">Correo Electrónico</th>
						<th scope="col" class="manage-column">Nivel Obtenido</th>
						<th scope="col" class="manage-column">Puntuación</th>
						<th scope="col" class="manage-column">Fecha Completa</th>
					</tr>
				</thead>
				<tbody>
					<?php if ( $results && count( $results ) > 0 ) : ?>
						<?php foreach ( $results as $row ) : ?>
							<tr>
								<td class="column-primary" data-colname="ID del Test"><strong>#<?php echo esc_html( $row->id ); ?></strong></td>
								<td data-colname="Correo Electrónico"><a href="mailto:<?php echo esc_attr( $row->email ); ?>"><?php echo esc_html( $row->email ); ?></a></td>
								<td data-colname="Nivel Obtenido"><strong><?php echo esc_html( $row->level ); ?></strong></td>
								<td data-colname="Puntuación"><?php echo esc_html( $row->score . ' / ' . $row->total ); ?> pts</td>
								<td data-colname="Fecha Completa"><?php echo esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $row->created_at ) ) ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="5">No se ha encontrado ninguna entrega por el momento.</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
