(function ($) {
    'use strict';

    $(document).ready(function () {
        // Elements
        const $startBtn = $('#ltf-start-btn');
        const $startScreen = $('#ltf-start-screen');
        const $questionsScreen = $('#ltf-questions-screen');
        const $emailScreen = $('#ltf-email-screen');
        const $resultScreen = $('#ltf-result-screen');

        const $progressBar = $('#ltf-progress-bar');
        const $currentQText = $('#ltf-current-q');
        const $totalQText = $('#ltf-total-q');
        const totalQs = parseInt($totalQText.text(), 10);

        const $submitDataBtn = $('#ltf-submit-data-btn');
        const $emailInput = $('#ltf-user-email');
        const $errorMsg = $('#ltf-error-msg');
        const $loader = $('#ltf-loader');

        // State
        let currentQuestionIndex = 0;

        // 1. Start Quiz
        $startBtn.on('click', function () {
            $startScreen.hide();
            $questionsScreen.show();
            updateProgress();
        });

        // 2. Radio Button Selection & Auto-advance (optional)
        $('.ltf-option-label input[type="radio"]').on('change', function () {
            // Remove selected class from siblings
            $(this).closest('.ltf-options').find('.ltf-option-label').removeClass('ltf-selected');
            // Add selected class to checked
            $(this).closest('.ltf-option-label').addClass('ltf-selected');

            // Enable NEXT or FINISH button of this block
            const block = $(this).closest('.ltf-question-block');
            block.find('.ltf-next-btn').prop('disabled', false);
            block.find('.ltf-finish-quiz-btn').prop('disabled', false);
        });

        // 3. Navigation: Next
        $('.ltf-next-btn').on('click', function () {
            const targetIndex = $(this).data('next');
            $('#ltf-q-' + currentQuestionIndex).hide();
            $('#ltf-q-' + targetIndex).show();
            currentQuestionIndex = targetIndex;
            updateProgress();
        });

        // 4. Navigation: Prev
        $('.ltf-prev-btn').on('click', function () {
            const targetIndex = $(this).data('prev');
            $('#ltf-q-' + currentQuestionIndex).hide();
            $('#ltf-q-' + targetIndex).show();
            currentQuestionIndex = targetIndex;
            updateProgress();
        });

        // 5. Finished Questions -> Show Email Screen
        $('.ltf-finish-quiz-btn').on('click', function () {
            $questionsScreen.hide();
            $emailScreen.show();
        });

        // Update Progress Bar
        function updateProgress() {
            $currentQText.text(currentQuestionIndex + 1);
            const percent = ((currentQuestionIndex) / totalQs) * 100;
            $progressBar.css('width', percent + '%');
        }

        // 6. Submit Data via AJAX
        $submitDataBtn.on('click', function () {
            const email = $emailInput.val().trim();

            // Simple email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                $errorMsg.text('Por favor, ingresa un correo electrónico válido.').show();
                return;
            }
            $errorMsg.hide();

            // Gather Answers
            const answersArray = $('#ltf-quiz-form').serializeArray();
            let formattedAnswers = {};

            // Si no contestó algunas, mandarlas vacías o no enviarlas.
            $.each(answersArray, function (i, field) {
                // field.name es "answer_XX", extraemos "XX"
                let id = field.name.replace('answer_', '');
                formattedAnswers[id] = field.value;
            });

            if (Object.keys(formattedAnswers).length === 0) {
                $errorMsg.text('No has seleccionado ninguna respuesta.').show();
                return;
            }

            // Show loader
            $loader.show();

            // AJAX Request
            $.ajax({
                url: ltf_ajax_obj.ajaxurl,
                type: 'POST',
                data: {
                    action: 'ltf_submit_quiz',
                    _ajax_nonce: ltf_ajax_obj.nonce,
                    email: email,
                    answers: formattedAnswers
                },
                success: function (response) {
                    $loader.hide();

                    if (response.success && response.data) {
                        // Populate results
                        $('#ltf-res-level').text(response.data.level);
                        $('#ltf-res-score').text(response.data.score);
                        $('#ltf-res-total').text(response.data.total);
                        $('#ltf-res-message').text(response.data.message);
                        $('#ltf-res-hook').text(response.data.hook);

                        // Show Results Screen
                        $emailScreen.hide();
                        $resultScreen.show();
                    } else {
                        $errorMsg.text(response.data.message || 'Error al procesar el cuestionario.').show();
                    }
                },
                error: function () {
                    $loader.hide();
                    $errorMsg.text('Ocurrió un error de conexión. Inténtalo de nuevo.').show();
                }
            });
        });

    });

})(jQuery);
