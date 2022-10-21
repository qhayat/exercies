<?php

namespace App\Service;

use App\Service\VisitFiles\Filter\FilterInterface;
use Symfony\Component\Finder\Finder;
use App\Service\VisitFiles\File;

class VisitFiles
{
    /**
     * Traverse Files & Directories.
     *
     * Return a list of every files filtered by given function.
     *
     * @param string $root
     * @param callable $filterFn
     * @return array<File>
     */
    public function visit(string $root, callable $filterFn): array
    {
        $files = [];
        /**
         * Ici il est possible d'utiliser la méthod "filter" du finder a condition que le callable soit modifié ...
         * Cela nous éviterait d'avoir le "foreach"
         */
        foreach (Finder::create()->files()->in($root) as $fileFromRoot) {
            $file = new File($fileFromRoot->getFilename());
            if (false !== $filterFn($file)) {
                $files[] = $file;
            }
        }

        return $files;
    }
}
