<?php

namespace Modules\Mail\App\Services;

class PlaceholderExtractor
{
    public function extract(string $subject, string $body): array
    {
        $all = $this->match($subject) + $this->match($body);
        return array_values(array_unique(array_keys($all)));
    }

    private function match(string $content): array
    {
        $found = [];
        if (preg_match_all('/\{\{\s*\$([A-Za-z_][A-Za-z0-9_]*)\s*\}\}/', $content, $m)) {
            foreach ($m[1] as $var) {
                $found[$var] = true;
            }
        }
        return $found;
    }


}
