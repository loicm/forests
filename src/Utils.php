<?php
namespace loicm\Forests;


class Utils
{
    public static function deleteRecursive($dir, $level = 0)
    {
        $dir = preg_replace('/\/$/', '', $dir);

        $files = scandir($dir);

        foreach($files as $file) {
            if ($file == '.' || $file == '..') { continue; }

            $file = $dir.'/'.$file;

            if (is_file($file)) {
                unlink($file);
            } elseif (is_dir($file)) {
                self::deleteRecursive($file, $level + 1);
            }
        }

        if ($level > 0) {
            rmdir($dir);
        }
    }

    public static function listRecursive($dir)
    {
        $dir = preg_replace('/\/$/', '', $dir);

        $files = scandir($dir);

        $results = array();

        foreach($files as $file) {
            if ($file == '.' || $file == '..') { continue; }

            $file = $dir.'/'.$file;

            if (is_file($file) &&
                is_readable($file) &&
                preg_match('/\.(md|markdown|mdown)$/', $file)) {

                $results[] = $file;

            } elseif (is_dir($file)) {
                $results = array_merge($results, self::listRecursive($file));
            }
        }

        return $results;
    }

    public static function getContentOnly($file_content)
    {
        $real_lines = [];

        $lines = explode("\n", $file_content);
        foreach($lines as $line) {
            if (!preg_match('/^[a-z]+\s*:\s*/', $line)) {
                $real_lines[] = $line;
            }
        }

        return implode("\n", $real_lines);
    }

    public static function copyAssets($from, $to)
    {
        $iterator = new  \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $from,
                \FilesystemIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach($iterator as $item) {
            $dest = $to.DIRECTORY_SEPARATOR.$iterator->getSubPathName();

            if ($item->isDir()) {
                mkdir($dest, 0755, true);
            } else {
                if (!in_array($item->getExtension(), array('md', 'html'))) {
                    copy($item, $dest);
                }
                if (in_array($item->getExtension(), array('jpg', 'jpeg'))) {
                    exec('convert '. $dest.' -resize 550 -quality 85 '.$dest);
                }
            }
        }
    }
}