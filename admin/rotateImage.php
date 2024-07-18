<?php
class RotateImage {
    private $setFilename = "";
    private $exifData = "";
    private $degrees = "";

    public function __construct($setFilename) {
        if (!file_exists($setFilename)) {
            throw new Exception('File not found.');
        }
        $this->setFilename = $setFilename;
    }

    public function processExifData() {
        $orientation = 0;
        $this->exifData = exif_read_data($this->setFilename);
        foreach ($this->exifData as $key => $val) {
            if (strtolower($key) == "orientation") {
                $orientation = $val;
                break;
            }
        }
        if ($orientation == 0) {
            $this->_setOrientationDegree(1);
        }
        $this->_setOrientationDegree($orientation);
    }

    private function _setOrientationDegree($orientation) {
        switch ($orientation) {
            case 1:
                $this->degrees = 0;
                break;
            case 8:
                $this->degrees = 90;
                break;
            case 3:
                $this->degrees = 180;
                break;
            case 6:
                $this->degrees = 270;
                break;
        }
    }

    public function rotateImage() {
        if ($this->degrees < 1) {
            return FALSE;
        }
        $image_data = imagecreatefromjpeg($this->setFilename);
        return imagerotate($image_data, $this->degrees, 0);
    }

    public function savedFileName($savedFilename) {
        $imageResource = $this->rotateImage();
        if ($imageResource !== FALSE) {
            imagejpeg($imageResource, $savedFilename);
        }
    }
}
?>