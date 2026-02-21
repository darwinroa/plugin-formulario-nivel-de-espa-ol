<?php
/**
 * Clase que registra y maneja el shortcode para mostrar el test de nivel
 * y además encola los scripts y estilos necesarios del lado público.
 */

class LTF_Shortcode
{

    public function __construct()
    {
        // Registrar el shortcode
        add_shortcode('test_de_nivel', [$this, 'render_shortcode']);

        // Hook para encolar scripts y estilos públicos
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Configurar handlers AJAX para procesar el test
        add_action('wp_ajax_ltf_submit_quiz', [$this, 'handle_quiz_submission']);
        add_action('wp_ajax_nopriv_ltf_submit_quiz', [$this, 'handle_quiz_submission']);
    }

    /**
     * Encola los estilos CSS y los scripts JS
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style('ltf-public-style', LTF_PLUGIN_URL . 'public/css/level-test-form.css', [], LTF_VERSION, 'all');

        wp_enqueue_script('ltf-public-script', LTF_PLUGIN_URL . 'public/js/level-test-form.js', ['jquery'], LTF_VERSION, true);

        // Pasar datos de PHP a JS, incluido admin-ajax.php y un nonce para seguridad
        wp_localize_script('ltf-public-script', 'ltf_ajax_obj', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ltf_submit_quiz')
        ]);
    }

    /**
     * Función que renderiza el shortcode
     */
    public function render_shortcode($atts)
    {
        ob_start();

        // Incluir la vista HTML
        include LTF_PLUGIN_DIR . 'public/partials/quiz-display.php';

        return ob_get_clean();
    }

    /**
     * Maneja la lógica de envío de las respuestas mediante AJAX
     */
    public function handle_quiz_submission()
    {
        check_ajax_referer('ltf_submit_quiz', '_ajax_nonce');

        $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
        $answers = isset($_POST['answers']) && is_array($_POST['answers']) ? $_POST['answers'] : [];

        if (!is_email($email)) {
            wp_send_json_error(['message' => 'El correo electrónico no es válido.']);
        }

        // 1. Evaluar Respuestas
        $result = LTF_Quiz_Engine::evaluate_answers($answers);

        // 2. Guardar en Base de Datos
        global $wpdb;
        $table_name = $wpdb->prefix . 'ltf_submissions';

        $inserted = $wpdb->insert(
            $table_name,
        [
            'email' => $email,
            'score' => $result['score'],
            'total' => $result['total'],
            'level' => $result['level'],
            'created_at' => current_time('mysql')
        ],
        ['%s', '%d', '%d', '%s', '%s']
        );

        if (false === $inserted) {
            wp_send_json_error(['message' => 'Error al guardar los datos en la base de datos.']);
        }

        // 3. Devolver resultados de vuelta al formulario
        wp_send_json_success($result);
    }
}