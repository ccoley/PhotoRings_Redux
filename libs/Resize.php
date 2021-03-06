<?php
/**
 * Class Resize handles the resizing of images. It will shrink or enlarge the
 * image in 1 of 5 ways:
 *
 * - Exact: Resize the image to the new dimensions with no regard for aspect
 *          ratio. If the old aspect ratio is not the same as the new aspect
 *          ratio then the resized image will look squished or stretched.
 * - Portrait: Resize the image to the new dimensions while preserving aspect
 *          ratio. Output height is the exact desired height, output width is
 *          calculated based on the old aspect ratio.
 * - Landscape: Resize the image to the new dimensions while preserving aspect
 *          ratio. Output width is the exact desired width, output height is
 *          calculated based on the old aspect ratio.
 * - Auto: Resize in either Portrait, Landscape, or Exact mode, based on the
 *          original dimensions. If the image is wider than it is tall, it
 *          resizes in Landscape mode. If the image is taller than it is wide,
 *          it resizes in Portrait mode. If the image is a square and the
 *          output width is greater than the output height, it resizes in
 *          Landscape mode. If the image is a square and the output width is
 *          less than the output height, it resizes in Portrait mode. If the
 *          image and the output are both squares, it resizes in Exact mode.
 * - Crop: Crop the image from the center so that it's aspect ratio matches the
 *          new aspect ratio, and then resize in Exact mode.
 *
 * ------------------------
 * | The Notorious B.U.G. |
 * ------------------------
 * The image being resized can not be located in PHP's "temporary" directory.
 * For some unknown reason, trying to resize an image located there will break
 * Resize and the final image will be completely black.
 *
 * The best work-around for this issue is to move your file somewhere else with
 * move_uploaded_file() before trying to Resize it. You could even move it to
 * the intended final location, and just overwrite it with the resized version
 * by passing the same path into saveImage().
 */
class Resize {
    // Class variables
    private $image;
    private $width;
    private $height;
    private $imageResized;
    
    public function __construct($fileName) {
        /*
         * NOTE: $fileName cannot be a location in PHP's "temporary" directory.
         * I'm not sure why, but that breaks Resize and the final image will be
         * totally black.
         *
         * The best was around this issue is to move your file somewhere else
         * with move_uploaded_file() before trying to Resize it. You could even
         * move it to the intended final location, and just overwrite it with
         * the resized version by passing the same path into saveImage().
         */

        // Open up the file
        $this->image = $this->openImage($fileName);
        
        // Get width and height
        $this->width  = imagesx($this->image);
        $this->height = imagesy($this->image);
    }
    
    private function openImage($file) {
        // Get extension
        $extension = strtolower(strrchr($file, '.'));
        
        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                $img = @imagecreatefromjpeg($file);
                break;
            case '.gif':
                $img = @imagecreatefromgif($file);
                break;
            case '.png':
                $img = @imagecreatefrompng($file);
                break;
            default:
                $img = false;
                break;
        }
        return $img;
    }
    
    public function resizeImage($newWidth, $newHeight, $option="auto") {
        // Get optimal width and height - based on $option
        $optionArray = $this->getDimensions($newWidth, $newHeight, strtolower($option));
        
        $optimalWidth  = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];
        
        // Resample - create image canvas of x, y size
        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        
        // Handle transparency
//        imagealphablending($this->imageResized, false);
//        imagesavealpha($this->imageResized, true);
        
        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
        
        // if option is 'crop', then crop too
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        }
    }
    
    private function getDimensions($newWidth, $newHeight, $option) {
        switch ($option) {
            case 'exact':
                $optimalWidth  = $newWidth;
                $optimalHeight = $newHeight;
                break;
            case 'portrait':
                $optimalWidth  = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight = $newHeight;
                break;
            case 'landscape':
                $optimalWidth  = $newWidth;
                $optimalHeight = $this->getSizeByFixedWidth($newWidth);
                break;
            case 'auto':
                $optionArray   = $this->getSizeByAuto($newWidth, $newHeight);
                $optimalWidth  = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            case 'crop':
                $optionArray   = $this->getOptimalCrop($newWidth, $newHeight);
                $optimalWidth  = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            default:
                return false;
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }
    
    private function getSizeByFixedHeight($newHeight) {
        $ratio = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;
        return $newWidth;
    }
    
    private function getSizeByFixedWidth($newWidth) {
        $ratio = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;
        return $newHeight;
    }
    
    private function getSizeByAuto($newWidth, $newHeight) {
        if ($this->height < $this->width) {
            // Image to be resized is wider (landscape)
            $optimalWidth  = $newWidth;
            $optimalHeight = $this->getSizeByFixedWidth($newWidth);
        } elseif ($this->height > $this->width) {
            // Image to be resized is taller (portrait)
            $optimalWidth  = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight = $newHeight;
        } else {
            // Image to be resized is a square
            if ($newHeight < $newWidth) {
                // Resizing a square image into a landscape image
                $optimalWidth  = $newWidth;
                $optimalHeight = $this->getSizeByFixedWidth($newWidth);
            } elseif ($newHeight > $newWidth) {
                // Resizing a square image into a portrait image
                $optimalWidth  = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight = $newHeight;
            } else {
                // Resizing a square image into a square image
                $optimalWidth  = $newWidth;
                $optimalHeight = $newHeight;
            }
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }
    
    private function getOptimalCrop($newWidth, $newHeight) {
        $widthRatio  = $this->width / $newWidth;
        $heightRatio = $this->height / $newHeight;
        
        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }
        
        $optimalWidth  = $this->width / $optimalRatio;
        $optimalHeight = $this->height / $optimalRatio;
        
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }
    
    // Crops an image from the center
    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight) {
        // Find center
        $centerX = ($optimalWidth / 2) - ($newWidth / 2);
        $centerY = ($optimalHeight / 2) - ($newHeight / 2);
        
        $crop = $this->imageResized;
        
        // Crop from center to exact requested size
        $this->imageResized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($this->imageResized, $crop, 0, 0, $centerX, $centerY, $newWidth, $newHeight, $newWidth, $newHeight);
    }
    
    public function saveImage($savePath, $imageQuality="100") {
        // Get extension
        $extension = strrchr($savePath, '.');
        $extension = strtolower($extension);
        
        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->imageResized, $savePath, $imageQuality);
                }
                break;
            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->imageResized, $savePath);
                }
                break;
            case '.png':
                // Scale quality from 0-100 to 0-9
                $scaleQuality = round(($imageQuality / 100) * 9);
                
                // Invert quality setting as 0 is best, not 9
                $invertScaleQuality = 9 - $scaleQuality;
                
                if (imagetypes() & IMG_PNG) {
                    imagepng($this->imageResized, $savePath, $invertScaleQuality);
                }
                break;
            default:
                // No extension - No save
                break;
        }
        imagedestroy($this->imageResized);
    }
}
?>