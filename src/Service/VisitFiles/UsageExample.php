<?php

namespace App\Service\VisitFiles;

use App\Service\VisitFiles;

class UsageExample
{
    /**
     * L'exemple devrait nous retourner un tableau contenant une instance de File pour les fichiers "conf.conf" et "txt.txt"
     *
     * @return array<File>
     */
    public function useIt(): array
    {
        return (new VisitFiles())->visit(__DIR__, function ($file) {
            $name = $file->name;
            for ($i = 0; $i < floor(strlen($name)); $i++) {
                if ($name[$i] != $name[strlen($name) - $i - 1]) {
                    return false;
                }
            }
            return true;
        });
    }
}
