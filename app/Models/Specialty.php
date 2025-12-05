<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    /** @use HasFactory<\Database\Factories\SpecialtyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // --- Relaciones de 1:N ---
    public function judgeProfiles() {
        return $this->hasMany(JudgeProfile::class);
    }

    /**
     * Detectar especialidad por nombre usando fuzzy matching
     */
    public static function detectFromName($input)
    {
        if (!$input || trim($input) === '') {
            return null;
        }

        $normalizedInput = strtolower(self::normalizeString($input));
        $specialties = self::all();

        $bestMatchId = null;
        $bestSimilarity = 0;
        $threshold = 50;

        foreach ($specialties as $specialty) {
            $normalizedSpecialty = strtolower(self::normalizeString($specialty->name));
            similar_text($normalizedInput, $normalizedSpecialty, $percent);

            if ($percent > $bestSimilarity) {
                $bestSimilarity = $percent;
                $bestMatchId = $specialty->id;
            }
        }

        return $bestSimilarity >= $threshold ? $bestMatchId : null;
    }

    private static function normalizeString($string)
    {
        $unwanted = ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ñ'=>'n',
                     'Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ñ'=>'N'];
        $string = strtr($string, $unwanted);
        $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
        return trim($string);
    }
}
