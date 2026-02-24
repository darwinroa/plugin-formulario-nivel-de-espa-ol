<?php
/**
 * Provee la vista HTML del formulario que se inyecta por el shortcode.
 *
 * @var array $questions Este array viene del engine
 */

// Si este archivo es llamado directamente, abortar.
if (!defined('WPINC')) {
    die;
}

// Obtener las preguntas usando el engine
$questions = LTF_Quiz_Engine::get_questions();
?>

<div id="ltf-quiz-wrapper" class="ltf-quiz-wrapper">

    <!-- 1. Pantalla de Bienvenida -->
    <div id="ltf-start-screen" class="ltf-screen ltf-active">
        <h2>Descubre tu Nivel de Español</h2>
        <p>Demuestra lo que sabes con 30 preguntas que van desde Principiante hasta Avanzado. Al finalizar, recibirás tu
            nivel estimado y recomendaciones personalizadas.</p>
        <button type="button" id="ltf-start-btn" class="ltf-btn ltf-btn-primary">¡Comenzar el test!</button>
    </div>

    <!-- 2. Formulario de Preguntas -->
    <div id="ltf-questions-screen" class="ltf-screen" style="display: none;">

        <!-- Barra de Progreso -->
        <div class="ltf-progress-container">
            <div class="ltf-progress-bar" id="ltf-progress-bar" style="width: 0%;"></div>
            <p class="ltf-progress-text"><span id="ltf-current-q">1</span> de <span id="ltf-total-q">
                    <?php echo count($questions); ?>
                </span></p>
        </div>

        <form id="ltf-quiz-form">
            <?php foreach ($questions as $index => $q): ?>
            <div class="ltf-question-block" id="ltf-q-<?php echo esc_attr($index); ?>" <?php echo $index > 0 ? 
        'style="display: none;"' : ''; ?>>
                <h3 class="ltf-question-text">
                    <?php echo esc_html(($index + 1) . '. ' . $q['text']); ?>
                </h3>
                <div class="ltf-options">
                    <?php foreach ($q['options'] as $opt_idx => $opt_text): ?>
                    <label class="ltf-option-label">
                        <input type="radio" name="answer_<?php echo esc_attr($q['id']); ?>"
                            value="<?php echo esc_attr($opt_idx); ?>" required>
                        <span class="ltf-option-text">
                            <?php echo esc_html($opt_text); ?>
                        </span>
                    </label>
                    <?php
    endforeach; ?>
                </div>
                <div class="ltf-nav-buttons">
                    <?php if ($index > 0): ?>
                    <button type="button" class="ltf-btn ltf-btn-secondary ltf-prev-btn"
                        data-prev="<?php echo $index - 1; ?>">Anterior</button>
                    <?php
    else: ?>
                    <!-- Espaciador vacío para justificar el botón de Siguiente a la derecha -->
                    <span></span>
                    <?php
    endif; ?>

                    <?php if ($index < count($questions) - 1): ?>
                    <button type="button" class="ltf-btn ltf-btn-primary ltf-next-btn"
                        data-next="<?php echo $index + 1; ?>" disabled>Siguiente</button>
                    <?php
    else: ?>
                    <button type="button" class="ltf-btn ltf-btn-success ltf-finish-quiz-btn"
                        disabled>Finalizar</button>
                    <?php
    endif; ?>
                </div>
            </div>
            <?php
endforeach; ?>
        </form>
    </div>

    <!-- 3. Pantalla de Captura de Email Email -->
    <div id="ltf-email-screen" class="ltf-screen" style="display: none;">
        <h2>¡Test completado!</h2>
        <p>Para ver tus resultados y conocer las recomendaciones personalizadas para que sigas aprendiendo, por favor
            ingresa tu correo electrónico.</p>

        <div class="ltf-email-form">
            <input type="email" id="ltf-user-email" class="ltf-input" placeholder="tu@email.com" required>
            <button type="button" id="ltf-submit-data-btn" class="ltf-btn ltf-btn-primary">Ver mis resultados</button>
        </div>
        <p id="ltf-error-msg" class="ltf-error" style="display: none;"></p>
    </div>

    <!-- 4. Pantalla de Resultados -->
    <div id="ltf-result-screen" class="ltf-screen" style="display: none;">
        <div class="ltf-result-header">
            <h2>Tu Nivel: <span id="ltf-res-level"></span></h2>
            <h3>Obtuviste <span id="ltf-res-score"></span> de <span id="ltf-res-total"></span> puntos.</h3>
        </div>
        <div class="ltf-result-message-box">
            <p id="ltf-res-message"></p>
        </div>
        <div class="ltf-result-hook-box">
            <p id="ltf-res-hook"></p>
        </div>
        <div class="ltf-result-actions" style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
            <a href="#" id="ltf-res-course-btn" class="ltf-btn ltf-btn-primary"
                style="display: none; text-decoration: none;">Ver curso recomendado</a>
            <button type="button" class="ltf-btn ltf-btn-secondary" onclick="location.reload();">Volver a
                intentar</button>
        </div>
    </div>

    <!-- Loader de AJAX -->
    <div id="ltf-loader" style="display:none;">
        <div class="ltf-spinner"></div>
        <p>Evaluando tus respuestas...</p>
    </div>

</div>