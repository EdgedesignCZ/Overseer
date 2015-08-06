<?php

namespace Edge\Overseer;

class Grep
{
    private $ignoredPatterns;

    public function __construct(array $ignoredPatterns)
    {
        $this->ignoredPatterns = $ignoredPatterns;
    }

    public function diffFiles($currentVersion, $previousFile)
    {
        if (file_exists($previousFile)) {
            ob_start();
            passthru("diff {$previousFile} {$currentVersion} | grep '>'");
            $diff = ob_get_clean();
        } elseif (file_exists($currentVersion)) {
            $diff = file_get_contents($currentVersion);
        } else {
            $diff = '';
        }
        return $this->filterOutIgnoredLines($diff);
    }

    public function filterOutIgnoredLines($text)
    {
        return array_reduce(
            $this->ignoredPatterns,
            function ($filteredText, $regexp) {
                return $this->invertGrep($regexp, $filteredText);
            },
            $text
        );
    }

    private function invertGrep($pattern, $text)
    {
        return implode("\n", preg_grep($pattern, explode("\n", $text), PREG_GREP_INVERT));
    }
}
