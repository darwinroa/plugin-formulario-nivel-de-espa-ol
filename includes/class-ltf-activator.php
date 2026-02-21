<?php
/**
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 */

class LTF_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        global $wpdb;

        // Definir el nombre de la tabla con el prefijo de WordPress
        $table_name = $wpdb->prefix . 'ltf_submissions';

        // Obtener el charset collate de la base de datos
        $charset_collate = $wpdb->get_charset_collate();

        // Sentencia SQL para crear la tabla
        $sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			email varchar(100) NOT NULL,
			score smallint(5) NOT NULL,
			total smallint(5) NOT NULL,
			level varchar(10) NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

        // Se requiere este archivo para usar dbDelta
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Crear o actualizar la tabla
        dbDelta($sql);
    }

}