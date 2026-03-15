<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /**
     * Export data to CSV.
     *
     * @param array $data 2D array of data.
     * @param string $filename Filename without extension.
     * @return StreamedResponse
     */
    public function toCsv(array $data, string $filename): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filename}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream(function() use($data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        }, 200, $headers);
    }
}
