<?php
/**
 * Clase encargada de manejar la lógica del cuestionario, 
 * calcular las respuestas y determinar el nivel final.
 */

class LTF_Quiz_Engine
{

    /**
     * Retorna la lista de todas las preguntas ordenadas por bloques.
     * 
     * @return array Lista de preguntas.
     */
    public static function get_questions()
    {
        return [
            // Bloque A1: Principiante
            ['id' => 1, 'block' => 'A1', 'text' => '¿Cómo ________ te llamas? — Me llamo Juan.', 'options' => ['tú', 'te', 'usted'], 'answer' => 1], // B
            ['id' => 2, 'block' => 'A1', 'text' => 'Nosotros ________ en Medellín desde hace dos días.', 'options' => ['somos', 'estamos', 'tenemos'], 'answer' => 1], // B
            ['id' => 3, 'block' => 'A1', 'text' => 'Mis padres ________ en una casa muy bonita cerca del parque.', 'options' => ['viven', 'vivís', 'vive'], 'answer' => 0], // A
            ['id' => 4, 'block' => 'A1', 'text' => '¿A qué hora ________ el curso de español?', 'options' => ['empieza', 'empiezas', 'empezas'], 'answer' => 0], // A
            ['id' => 5, 'block' => 'A1', 'text' => 'Yo no ________ hablar francés, solo español e inglés.', 'options' => ['conozco', 'sé', 'sabo'], 'answer' => 1], // B
            ['id' => 6, 'block' => 'A1', 'text' => '¿________ es tu maleta? — La roja.', 'options' => ['Qué', 'Cuál', 'Cómo'], 'answer' => 1], // B

            // Bloque A2: Elemental
            ['id' => 7, 'block' => 'A2', 'text' => 'Ayer ________ mucha fruta en el mercado de Paloquemao.', 'options' => ['compro', 'compré', 'compraré'], 'answer' => 1], // B
            ['id' => 8, 'block' => 'A2', 'text' => '¿Has estado ________ vez en Cartagena?', 'options' => ['alguna', 'algo', 'alguien'], 'answer' => 0], // A
            ['id' => 9, 'block' => 'A2', 'text' => 'Esta es mi habitación y esa de allí es la ________.', 'options' => ['tuya', 'tu', 'tuyo'], 'answer' => 0], // A
            ['id' => 10, 'block' => 'A2', 'text' => 'Mañana ________ a ir a caminar por el Eje Cafetero.', 'options' => ['vamos', 'irán', 'fuimos'], 'answer' => 0], // A
            ['id' => 11, 'block' => 'A2', 'text' => 'Me duele la cabeza, voy a ________ un poco.', 'options' => ['descansando', 'descansar', 'descansado'], 'answer' => 1], // B
            ['id' => 12, 'block' => 'A2', 'text' => 'El libro está ________ de la mesa.', 'options' => ['debajo', 'abajo', 'bajo'], 'answer' => 0], // A

            // Bloque B1: Intermedio
            ['id' => 13, 'block' => 'B1', 'text' => 'Cuando ________ niño, siempre jugaba en la calle.', 'options' => ['fui', 'era', 'soy'], 'answer' => 1], // B
            ['id' => 14, 'block' => 'B1', 'text' => 'No creo que ________ sol hoy, está muy nublado.', 'options' => ['hace', 'haga', 'hará'], 'answer' => 1], // B
            ['id' => 15, 'block' => 'B1', 'text' => '¿Le diste el regalo a María? — Sí, ya ________ di.', 'options' => ['se lo', 'le lo', 'lo le'], 'answer' => 0], // A
            ['id' => 16, 'block' => 'B1', 'text' => 'Espero que ________ un buen viaje a Colombia.', 'options' => ['tienes', 'tengas', 'tenas'], 'answer' => 1], // B
            ['id' => 17, 'block' => 'B1', 'text' => 'Si ________ más dinero, viajaría por todo el mundo.', 'options' => ['tuviera', 'tendría', 'tengo'], 'answer' => 0], // A
            ['id' => 18, 'block' => 'B1', 'text' => 'He buscado mis llaves pero no ________ encuentro.', 'options' => ['las', 'los', 'les'], 'answer' => 0], // A

            // Bloque B2: Intermedio Alto
            ['id' => 19, 'block' => 'B2', 'text' => 'Si me hubieras avisado, yo te ________ a buscar al aeropuerto.', 'options' => ['habría ido', 'hubiera ido', 'habré ido'], 'answer' => 0], // A
            ['id' => 20, 'block' => 'B2', 'text' => 'Aunque ________ muy cansado, siguió estudiando para el examen.', 'options' => ['estaba', 'esté', 'estuvo'], 'answer' => 0], // A
            ['id' => 21, 'block' => 'B2', 'text' => 'La casa ________ construida en 1920.', 'options' => ['fue', 'estuvo', 'ha sido'], 'answer' => 0], // A
            ['id' => 22, 'block' => 'B2', 'text' => 'No me parece que esa ________ la mejor decisión.', 'options' => ['es', 'sea', 'será'], 'answer' => 1], // B
            ['id' => 23, 'block' => 'B2', 'text' => '________ termine la clase, iré a almorzar.', 'options' => ['En cuanto', 'Mientras', 'Para que'], 'answer' => 0], // A
            ['id' => 24, 'block' => 'B2', 'text' => '¿Te importa que ________ la ventana?', 'options' => ['abro', 'abra', 'abriera'], 'answer' => 1], // B

            // Bloque C1: Avanzado
            ['id' => 25, 'block' => 'C1', 'text' => 'Dudo mucho que para el próximo año ya ________ una solución.', 'options' => ['hayan encontrado', 'han encontrado', 'hubieran encontrado'], 'answer' => 0], // A
            ['id' => 26, 'block' => 'C1', 'text' => 'Por mucho que ________, no consigue aprender las preposiciones.', 'options' => ['estudia', 'estudie', 'estudiará'], 'answer' => 1], // B
            ['id' => 27, 'block' => 'C1', 'text' => 'Le dije que lo hiciera ________ antes posible.', 'options' => ['lo', 'cuanto', 'tan'], 'answer' => 0], // A
            ['id' => 28, 'block' => 'C1', 'text' => '¡Quién ________ hablar español como un nativo!', 'options' => ['supiera', 'sabe', 'sabría'], 'answer' => 0], // A
            ['id' => 29, 'block' => 'C1', 'text' => 'Se comportó como si no ________ nada de lo que pasó.', 'options' => ['supiera', 'sepa', 'sabe'], 'answer' => 0], // A
            ['id' => 30, 'block' => 'C1', 'text' => 'No es que no ________ ir, es que no tengo tiempo.', 'options' => ['quiero', 'quiera', 'querría'], 'answer' => 1], // B
        ];
    }

    /**
     * Evalúa las respuestas enviadas por el usuario.
     * 
     * @param array $user_answers Array asociativo [ id_pregunta => index_respuesta ].
     * @return array Array con el puntaje total y los detalles por pregunta.
     */
    public static function evaluate_answers($user_answers)
    {
        $questions = self::get_questions();
        $score = 0;
        $details = [];

        foreach ($questions as $q) {
            $q_id = $q['id'];
            $is_correct = false;
            $user_choice = isset($user_answers[$q_id]) ? intval($user_answers[$q_id]) : -1;

            if ($user_choice === $q['answer']) {
                $score++;
                $is_correct = true;
            }

            $details[$q_id] = [
                'is_correct' => $is_correct,
                'user_choice' => $user_choice,
                'correct_choice' => $q['answer']
            ];
        }

        $level_data = self::get_level_data($score);

        return [
            'score' => $score,
            'total' => count($questions),
            'level' => $level_data['level'],
            'message' => $level_data['message'],
            'hook' => $level_data['hook'],
            'course_url' => $level_data['course_url'],
            'details' => $details
        ];
    }

    /**
     * Devuelve la categoría de nivel, mensaje y gancho basado en el puntaje.
     * 
     * @param int $score Puntaje total obtenido.
     * @return array Datos del nivel.
     */
    public static function get_level_data($score)
    {
        if ($score <= 6) {
            return [
                'level' => 'A1',
                'title' => 'Nivel A1 (Principiante)',
                'message' => '¡Buen comienzo! Estás dando tus primeros pasos en el español. Eres capaz de entender expresiones cotidianas y presentarte.',
                'hook' => 'Para que tu llegada a Colombia sea perfecta, te recomendamos nuestro Curso de Principiantes y un alojamiento con anfitrión local en Bogotá para que practiques desde el primer día.',
                'course_url' => get_option('ltf_course_link_a1', '')
            ];
        }
        elseif ($score <= 12) {
            return [
                'level' => 'A2',
                'title' => 'Nivel A2 (Elemental)',
                'message' => '¡Vas por buen camino! Puedes comunicarte en tareas sencillas y hablar de tu pasado de forma básica.',
                'hook' => 'Es hora de soltar la lengua. Nuestro Curso principiante, para que repases todo el nivel A1 y hagas del A2 en adelante te dará la confianza para moverte por las calles de Medellín. ¡Mira estos apartamentos para estudiantes que tenemos para ti!',
                'course_url' => get_option('ltf_course_link_a2', '')
            ];
        }
        elseif ($score <= 18) {
            return [
                'level' => 'B1',
                'title' => 'Nivel B1 (Intermedio)',
                'message' => '¡Felicidades! Tienes un nivel intermedio. Puedes describir experiencias, deseos y viajar por Colombia con autonomía.',
                'hook' => 'Para alcanzar la fluidez total, necesitas nuestro Curso Intermedio. Te sugerimos una inmersión en el Eje Cafetero, hospedándote en una de nuestras fincas asociadas.',
                'course_url' => get_option('ltf_course_link_b1', '')
            ];
        }
        elseif ($score <= 24) {
            return [
                'level' => 'B2',
                'title' => 'Nivel B2 (Intermedio Alto)',
                'message' => '¡Excelente! Entiendes ideas complejas y puedes relacionarte con hablantes nativos con suficiente fluidez.',
                'hook' => 'Estás a un paso de la perfección. Te recomendamos nuestro Curso intermedio y un paquete turístico de aventura en Santander para poner a prueba tu español.',
                'course_url' => get_option('ltf_course_link_b2', '')
            ];
        }
        else {
            return [
                'level' => 'C1',
                'title' => 'Nivel C1 (Avanzado)',
                'message' => '¡Eres casi un nativo! Comprendes textos extensos y usas el idioma de forma flexible y efectiva.',
                'hook' => 'No dejes que tu español se oxide. Únete a nuestro curso nivel C1 y vive una experiencia de lujo en Cartagena mientras actúas como embajador de nuestra lengua.',
                'course_url' => get_option('ltf_course_link_c1', '')
            ];
        }
    }
}