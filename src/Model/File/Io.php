<?php
namespace Model\File;

class Io
{
    /**
     * @param $file
     * @return bool
     */
    public function fileExists($file)
    {
        return file_exists($file);
    }

    /**
     * @param $filename
     * @return string
     */
    public function getContents($filename)
    {
        return file_get_contents($filename);
    }

    /**
     * @param $filename
     * @param $data
     * @return int
     */
    public function putContents($filename, $data)
    {
        return file_put_contents($filename, $data);
    }
}
