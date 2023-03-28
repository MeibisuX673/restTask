<?php

namespace app\Controller\Traits;
trait getDataVerbPutPatch {
    private function getData(string $file): array
    {

        preg_match_all('/"[a-zA-Z_]+"\s+[A-Za-z]+/', $file, $matches, PREG_SET_ORDER);

        $data = array();

        foreach ($matches as $value) {

            $replacemnd = str_replace("\"", '', $value[0]);
            $replacemnd = preg_replace('/\s+/', ' ', $replacemnd);

            $atr = explode(' ', $replacemnd);
            $data[$atr[0]] = $atr[1];
        }

        return $data;
    }
}