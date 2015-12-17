<?php


class CsvFileService
{
    public static function getFileHeaders($filePath, $delimiter = ',', $number = 1)
    {
        ini_set('auto_detect_line_endings', true);
        $line = fgets(fopen($filePath, 'r'));
        return explode($delimiter, strtolower(trim($line)));
    }

    public static function getFileFirstLine($filePath, $delimiter = ',')
    {
        ini_set('auto_detect_line_endings', true);
        $line = fgets(fopen($filePath, 'r'));
        return $line;
    }

    public static function getLinesFromFile($filePath, $number = 1)
    {
        $lines  = [];
        $handle = fopen($filePath, "r");
        if ($handle) {
            $lineNumber = 0;
            while (($line = fgets($handle)) !== false) {
                $lines[$lineNumber] = $line;
                $lineNumber++;
                if ($lineNumber >= $number)
                    break;
            }
            fclose($handle);
        }
        return $lines;
    }

    public static function getFileHeaderFromLines($lines, $delimiter)
    {
        $headers = explode($delimiter, strtolower(trim($lines[0])));
        return $headers;
    }

    public static function toCsv($results, $preContent = "", $postContent = "", $delimiter=",")
    {
        $csv = $preContent."\n";
        foreach ($results as $result)
            $csv .= implode($delimiter, array_map(function($value) {if (empty($value));return '"'.$value.'"';}, array_values($result)))."\n";
        $csv .= $postContent;
        return $csv;
    }

    public static function csvToArray($csvPath, $skipHeader = true)
    {
        ini_set('auto_detect_line_endings', true);
        $lines      = [];
        $fp         = fopen($csvPath, "r");
        $i=0;
        while (($line = fgetcsv($fp)) !== FALSE) {
            $i++;
            if($i==1 && $skipHeader)
                continue;
            $lines[] = $line;
        }
        fclose($fp);
        return $lines;
    }

    public static function toXls($results, $preContent = "", $postContent = "")
    {
        $csv = $preContent."\r\n";
        foreach ($results as $result)
            $csv .= implode("\t", array_map(function($value) {return $value;}, array_values($result)))."\r\n";
        $csv .= $postContent;
        return $csv;
    }
}