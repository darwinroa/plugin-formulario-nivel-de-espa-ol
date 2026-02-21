<?php
/**
 * Plugin Name:       Level Test Form
 * Plugin URI:        https://tusitio.com/level-test-form
 * Description:       Plugin para crear un test de nivel de español, procesa respuestas y calcula el nivel.
 * Version:           1.0.0
 * Author:            Darwin Roa
 * Author URI:        https://github.com/darwinroa
 * License:           GPL-2.0+
 * Text Domain:       level-test-form
 * Domain Path:       /languages
 */

// Si este archivo es llamado directamente, abortar la ejecución por seguridad.
if (!defined('WPINC')) {
    die;
}

// Definir constantes útiles del plugin
define('LTF_VERSION', '1.0.0');
define('LTF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LTF_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Requerir todos los archivos principales creados
 */

// 1. Archivo de Activación (Lógica de instalación, tablas de BD)
require_once LTF_PLUGIN_DIR . 'includes/class-ltf-activator.php';

// 2. El "cerebro": procesa respuestas y calcula el nivel
require_once LTF_PLUGIN_DIR . 'includes/class-ltf-quiz-engine.php';

// 3. Registro y manejo del shortcode [test_de_nivel]
require_once LTF_PLUGIN_DIR . 'includes/class-ltf-shortcode.php';

// 4. Panel administrativo y reportes
require_once LTF_PLUGIN_DIR . 'admin/class-ltf-admin.php';

/**
 * Función que se ejecuta en la activación del plugin
 */
function activate_level_test_form()
{
    LTF_Activator::activate();
}
register_activation_hook(__FILE__, 'activate_level_test_form');

/**
 * Función principal para iniciar los componentes del plugin
 */
function run_level_test_form()
{
    $plugin_admin = new LTF_Admin();
    $plugin_shortcode = new LTF_Shortcode();
}

/**
 * Asegurar que la tabla existe (Failsafe)
 */
function ltf_check_db_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ltf_submissions';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        LTF_Activator::activate();
    }
}
add_action('plugins_loaded', 'ltf_check_db_table');

// Iniciar el plugin
run_level_test_form();