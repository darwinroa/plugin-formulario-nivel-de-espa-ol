<?php
/**
 * La funcionalidad de administración del plugin.
 */
class LTF_Admin
{

	public function __construct()
	{
		add_action('admin_menu', [$this, 'add_plugin_admin_menu']);
		add_action('admin_init', [$this, 'register_settings']);
		add_action('admin_post_ltf_export_csv', [$this, 'export_to_csv']);
		add_action('admin_post_ltf_delete_data', [$this, 'delete_data']);
	}

	/**
	 * Registra las opciones en la base de datos de WordPress
	 */
	public function register_settings()
	{
		register_setting('ltf_options_group', 'ltf_course_link_a1');
		register_setting('ltf_options_group', 'ltf_course_link_a2');
		register_setting('ltf_options_group', 'ltf_course_link_b1');
		register_setting('ltf_options_group', 'ltf_course_link_b2');
		register_setting('ltf_options_group', 'ltf_course_link_c1');
	}

	/**
	 * Registra el menú en el panel de administración de WordPress
	 */
	public function add_plugin_admin_menu()
	{
		// Menú principal
		add_menu_page(
			'Test de Nivel', // page_title
			'Test de Nivel', // menu_title
			'manage_options', // capability
			'level-test-form', // menu_slug
		[$this, 'display_plugin_setup_page'], // callback
			'dashicons-clipboard', // icon
			25 // position
		);

		// Submenú para Resultados (repite el item principal)
		add_submenu_page(
			'level-test-form', // parent_slug
			'Resultados', // page_title
			'Resultados', // menu_title
			'manage_options', // capability
			'level-test-form', // menu_slug
		[$this, 'display_plugin_setup_page'] // callback
		);

		// Submenú para la Configuración
		add_submenu_page(
			'level-test-form', // parent_slug
			'Configuración de Enlaces', // page_title
			'Configuración', // menu_title
			'manage_options', // capability
			'ltf-settings', // menu_slug
		[$this, 'display_settings_page'] // callback
		);
	}

	/**
	 * Renderiza la interfaz de la página de administración
	 */
	public function display_plugin_setup_page()
	{
		if (!current_user_can('manage_options')) {
			return;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'ltf_submissions';

		// Obtener todas las sumisiones
		$results = $wpdb->get_results("SELECT * FROM {$table_name} ORDER BY created_at DESC");
?>
<div class="wrap">
	<h1 class="wp-heading-inline">Resultados del Test de Nivel</h1>
	<a href="<?php echo esc_url(admin_url('admin-post.php?action=ltf_export_csv')); ?>"
		class="page-title-action">Exportar a CSV</a>

	<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
		style="display:inline-block; margin-left: 10px;" onsubmit="return confirmDelete(this);">
		<input type="hidden" name="action" value="ltf_delete_data">
		<?php wp_nonce_field('ltf_delete_action', 'ltf_delete_nonce'); ?>
		<input type="text" id="ltf_delete_confirm" placeholder="Escribe 'delete' para borrar" style="width: 180px;">
		<button type="submit" class="button button-secondary" style="color: #d63638; border-color: #d63638;">Eliminar
			todos</button>
	</form>

	<script>
		function confirmDelete(form) {
			var input = form.querySelector('#ltf_delete_confirm').value;
			if (input !== 'delete') {
				alert("Debes escribir la palabra 'delete' (en minúsculas) exactamente como se indica para confirmar la eliminación de datos.");
				return false;
			}
			return confirm("¿Estás 100% seguro de eliminar todos los resultados? Esta acción no se puede deshacer.");
		}
	</script>

	<hr class="wp-header-end">

	<?php if (isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
	<div class="notice notice-success is-dismissible">
		<p>Todos los resultados han sido eliminados correctamente.</p>
	</div>
	<?php
		endif; ?>

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
			<?php if ($results && count($results) > 0): ?>
			<?php foreach ($results as $row): ?>
			<tr>
				<td class="column-primary" data-colname="ID del Test"><strong>#
						<?php echo esc_html($row->id); ?>
					</strong></td>
				<td data-colname="Correo Electrónico"><a href="mailto:<?php echo esc_attr($row->email); ?>">
						<?php echo esc_html($row->email); ?>
					</a></td>
				<td data-colname="Nivel Obtenido"><strong>
						<?php echo esc_html($row->level); ?>
					</strong></td>
				<td data-colname="Puntuación">
					<?php echo esc_html($row->score . ' / ' . $row->total); ?> pts
				</td>
				<td data-colname="Fecha Completa">
					<?php echo esc_html(wp_date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($row->created_at))); ?>
				</td>
			</tr>
			<?php
			endforeach; ?>
			<?php
		else: ?>
			<tr>
				<td colspan="5">No se ha encontrado ninguna entrega por el momento.</td>
			</tr>
			<?php
		endif; ?>
		</tbody>
	</table>
</div>
<?php
	}

	/**
	 * Renderiza la interfaz de la configuración
	 */
	public function display_settings_page()
	{
		if (!current_user_can('manage_options')) {
			return;
		}
?>
<div class="wrap">
	<h1>Configuración de Enlaces de Cursos</h1>
	<p>Ingresa las URLs de los cursos recomendados para cada nivel. Al finalizar el test, si un nivel tiene configurado
		un enlace aquí, se le mostrará un botón de llamada a la acción al estudiante. (Puedes dejarlas vacías si no hay
		curso para ese nivel).</p>

	<form method="post" action="options.php">
		<?php settings_fields('ltf_options_group'); ?>
		<?php do_settings_sections('ltf_options_group'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">URL Curso Nivel A1</th>
				<td><input type="url" name="ltf_course_link_a1"
						value="<?php echo esc_attr(get_option('ltf_course_link_a1')); ?>" class="regular-text"
						placeholder="https://..." /></td>
			</tr>
			<tr valign="top">
				<th scope="row">URL Curso Nivel A2</th>
				<td><input type="url" name="ltf_course_link_a2"
						value="<?php echo esc_attr(get_option('ltf_course_link_a2')); ?>" class="regular-text"
						placeholder="https://..." /></td>
			</tr>
			<tr valign="top">
				<th scope="row">URL Curso Nivel B1</th>
				<td><input type="url" name="ltf_course_link_b1"
						value="<?php echo esc_attr(get_option('ltf_course_link_b1')); ?>" class="regular-text"
						placeholder="https://..." /></td>
			</tr>
			<tr valign="top">
				<th scope="row">URL Curso Nivel B2</th>
				<td><input type="url" name="ltf_course_link_b2"
						value="<?php echo esc_attr(get_option('ltf_course_link_b2')); ?>" class="regular-text"
						placeholder="https://..." /></td>
			</tr>
			<tr valign="top">
				<th scope="row">URL Curso Nivel C1</th>
				<td><input type="url" name="ltf_course_link_c1"
						value="<?php echo esc_attr(get_option('ltf_course_link_c1')); ?>" class="regular-text"
						placeholder="https://..." /></td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
</div>
<?php
	}

	/**
	 * Exporta los resultados a un archivo CSV
	 */
	public function export_to_csv()
	{
		if (!current_user_can('manage_options')) {
			wp_die('No tienes permisos para realizar esta acción.');
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'ltf_submissions';
		$results = $wpdb->get_results("SELECT * FROM {$table_name} ORDER BY created_at DESC", ARRAY_A);

		$filename = 'resultados_test_nivel_' . wp_date('Ymd_His') . '.csv';

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $filename);

		$output = fopen('php://output', 'w');

		// BOM para UTF-8 (Excel)
		fputs($output, "\xEF\xBB\xBF");

		// Cabeceras
		fputcsv($output, ['ID', 'Correo Electrónico', 'Puntuación', 'Total', 'Nivel', 'Fecha']);

		if (!empty($results)) {
			foreach ($results as $row) {
				fputcsv($output, [
					$row['id'],
					$row['email'],
					$row['score'],
					$row['total'],
					$row['level'],
					wp_date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($row['created_at']))
				]);
			}
		}

		fclose($output);
		exit;
	}

	/**
	 * Elimina permanentemente toda la data si se comprobó
	 */
	public function delete_data()
	{
		if (!current_user_can('manage_options')) {
			wp_die('No tienes permisos para realizar esta acción.');
		}

		check_admin_referer('ltf_delete_action', 'ltf_delete_nonce');

		global $wpdb;
		$table_name = $wpdb->prefix . 'ltf_submissions';

		// Trunca la tabla para reiniciar los IDs o Delete para limpiar registros
		$wpdb->query("TRUNCATE TABLE {$table_name}");

		// Redireccionar adjuntando "deleted=1" en la URL actual de la página
		wp_redirect(add_query_arg(['page' => 'level-test-form', 'deleted' => '1'], admin_url('admin.php')));
		exit;
	}
}