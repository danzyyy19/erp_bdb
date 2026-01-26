<?php

namespace App\Traits;

trait GeneratesDocumentNumber
{
    /**
     * Convert month number to Roman numeral
     */
    protected static function toRomanMonth(int $month): string
    {
        $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romans[$month - 1] ?? 'I';
    }

    /**
     * Get the next sequence number for a model
     */
    protected static function getNextSequence(string $model, string $numberField, int $year, int $month): int
    {
        $lastRecord = $model::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRecord) {
            // Extract sequence from format like "SPK/001/BDB/XII/24" or "RLI/001/BDB/XII/2024"
            $parts = explode('/', $lastRecord->$numberField);
            $sequence = isset($parts[1]) ? (int) $parts[1] : 0;
            return $sequence + 1;
        }

        return 1;
    }
}
