<?php

namespace App\Support;

class InspiringEs
{
    /**
     * Obtener una colección de frases inspiradoras en español.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function quotes()
    {
        $frases = [
            "La vida es 10% lo que nos sucede y 90% cómo reaccionamos. - Charles R. Swindoll",
            "El éxito es la suma de pequeños esfuerzos repetidos día tras día. - Robert Collier",
            "No cuentes los días, haz que los días cuenten. - Muhammad Ali",
            "La única forma de hacer un gran trabajo es amar lo que haces. - Steve Jobs",
            "El futuro pertenece a quienes creen en la belleza de sus sueños. - Eleanor Roosevelt",
            "Haz de cada día tu obra maestra. - John Wooden",
            "La motivación nos impulsa a comenzar y el hábito nos permite continuar. - Jim Ryun",
            "Cree en ti y todo será posible. - Anónimo",
            "El único límite para nuestra realización de mañana es nuestras dudas de hoy. - Franklin D. Roosevelt",
            "No sueñes tu vida, vive tu sueño. - Anónimo",
            "Lo que no te mata, te hace más fuerte. - Friedrich Nietzsche",
            "El éxito no es la clave de la felicidad. La felicidad es la clave del éxito. - Albert Schweitzer",
            "Cada día es una nueva oportunidad para cambiar tu vida. - Anónimo",
            "La perseverancia es la madre del éxito. - Proverbio español",
            "Si puedes soñarlo, puedes lograrlo. - Walt Disney",
            "La verdadera sabiduría está en reconocer la propia ignorancia. - Sócrates",
            "El cambio es ley de vida. Aquellos que solo miran al pasado o al presente se perderán el futuro. - John F. Kennedy",
            "Nunca es demasiado tarde para ser lo que podrías haber sido. - George Eliot",
            "La felicidad no es algo hecho. Viene de tus propias acciones. - Dalai Lama",
            "Lo único imposible es aquello que no intentas. - Anónimo",
        ];

        return collect($frases);
    }
}
