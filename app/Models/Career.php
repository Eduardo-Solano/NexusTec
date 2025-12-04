<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    /** @use HasFactory<\Database\Factories\CareerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    // --- Relaciones de 1:N ---
    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class);
    }
    public static function detectFromName($input)
    {
        if (!$input || trim($input) === '') {
            return null;
        }

        // Normalizar el texto del CSV
        $normalizedInput = strtolower(self::normalizeCareerString($input));

        // Obtener todas las carreras
        $careers = self::all();

        $bestMatchId = null;
        $bestSimilarity = 0;
        $threshold = 50; // 50% requerido (tu elección)

        foreach ($careers as $career) {

            // Normalizar nombre de la carrera en BD
            $normalizedCareer = strtolower(self::normalizeCareerString($career->name));

            // Calcular similitud
            similar_text($normalizedInput, $normalizedCareer, $percent);

            // Guardar el mejor resultado
            if ($percent > $bestSimilarity) {
                $bestSimilarity = $percent;
                $bestMatchId = $career->id;
            }
        }

        // Si no supera el 50%, no lo asignamos
        return $bestSimilarity >= $threshold ? $bestMatchId : null;
    }

    private static function normalizeCareerString($string)
    {
        // Quitar acentos
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

        // Convertir a minúsculas y eliminar caracteres raros
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s]/', '', $string);

        // Quitar palabras comunes
        $stopWords = ['ingenieria', 'ing', 'licenciatura', 'lic', 'en', 'de', 'y'];
        $words = explode(' ', $string);

        $filtered = array_diff($words, $stopWords);

        return implode(' ', $filtered);
    }

}
