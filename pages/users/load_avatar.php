<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
/**
* Handle file uploads via XMLHttpRequest
*/
class qqUploadedFileXhr {
    /**
    * Save the file to the specified path
    * @return boolean TRUE on success
    */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()){            
            return false;
        }

        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
* Handle file uploads via regular form post (uses the $_FILES array)
*/
class qqUploadedFileForm {  
    /**
    * Save the file to the specified path
    * @return boolean TRUE on success
    */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }

    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }

    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }

    /**
    * Returns array('success'=>true) or array('error'=>'error message')
    */
    function handleUpload($uploadDirectory, $replaceOldFile = TRUE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. $uploadDirectory Upload directory isn't writable.");
        }

        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty');
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }

        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }

        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return array('success'=>true);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
            'The upload was cancelled, or server error encountered');
        }

    }    
}

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array("jpeg", "jpg", "gif", "png", "bmp");
// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/");


$dir_pics = $_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/";
$handle = new upload($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $_GET['qqfile']);
$handle->image_resize            = true;
$handle->image_ratio_crop        = 'L';
$handle->file_overwrite          = true;
//$handle->image_ratio_x           = false;
$handle->image_y                 = 165;
$handle->image_x                 = 165;
$handle->file_new_name_body      = User::find_by_login($_GET['login'])->usr_id . "_big";
$handle->image_convert           = 'jpg';
$handle->jpeg_quality            = 100;
$handle->Process($dir_pics);

$handle2 = new upload($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $_GET['qqfile']);
$handle2->image_resize            = true;
$handle2->image_ratio_crop        = 'L';
$handle2->file_overwrite          = true;
//$handle2->image_ratio_x           = false;
$handle2->image_y                 = 50;
$handle2->image_x                 = 50;
$handle2->file_new_name_body      = User::find_by_login($_GET['login'])->usr_id;
$handle2->image_convert           = 'jpg';
$handle2->jpeg_quality            = 100;
$handle2->Process($dir_pics);

unlink($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $_GET['qqfile']);
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
