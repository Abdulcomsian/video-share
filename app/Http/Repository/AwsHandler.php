<?php
namespace App\Http\Repository;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class AwsHandler{

    protected $s3;
    protected $bucketName;

    function __construct(){
        $this->s3 = new S3Client([
                                'version' => 'latest',
                                'region'  => env('AWS_DEFAULT_REGION'), // 
                                'credentials' => [
                                    'key'    => env('AWS_ACCESS_KEY_ID'),
                                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                                ],
                            ]);
       $this->bucketName =  env('AWS_BUCKET');
    }


    public function createFolder($folderName){
        try {
            $this->s3->putObject([
                'Bucket' => $this->bucketName,
                'Key'    => $folderName."/",
                'Body'   => '',
            ]);
        
            return ["success" => true , "msg" => "Folder Created Successfully"];
          } catch (S3Exception $e) {
            return ["success" => false , "error" => "Error creating folder: " . $e->getMessage()];
        }
    }

    public function deleteFolder($folderName){
        try {
        // list of files in the bucket
        $objects = $this->s3->listObjects([
            'Bucket' => $this->bucketName,
            'Prefix' => $folderName."/",
        ]);

        // Delete each file in the folder
        foreach ($objects['Contents'] as $object) {
            $this->s3->deleteObject([
                'Bucket' => $this->bucketName,
                'Key'    => $object['Key'],
            ]);
        }

        // Delete the folder itself
        $this->s3->deleteObject([
            'Bucket' => $this->bucketName,
            'Key'    => $folderName."/",
        ]);

        return ["success" => true , "msg" => "Folder Deleted Successfully"];

        } catch (S3Exception $e) {

            return ["success" => false , "error" => "Error deleting folder: " . $e->getMessage()];
        
        }
    }


    public function updateFolder($folderName , $newFolderName){
        try{

            $oldFolderPrefix = $folderName."/";
            $newFolderPrefix = $newFolderName."/";
            $objects = $this->s3->listObjects([
                'Bucket' => $this->bucketName,
                'Prefix' => $folderName."/",
            ]);
            
            // Copy each object from the old folder to the new folder
            foreach ($objects['Contents'] as $object) {
                $sourceKey = $object['Key'];
                $destinationKey = str_replace($oldFolderPrefix, $newFolderPrefix, $sourceKey);
            
                $this->s3->copyObject([
                    'Bucket'     => $this->bucketName,
                    'CopySource' => "{$this->bucketName}/{$sourceKey}",
                    'Key'        => $destinationKey,
                ]);
            }
            
            // Delete the old folder and its contents
            foreach ($objects['Contents'] as $object) {
                $this->s3->deleteObject([
                    'Bucket' => $this->bucketName,
                    'Key'    => $object['Key'],
                ]);
            }
            
            // Delete the old folder itself
            $this->s3->deleteObject([
                'Bucket' => $this->bucketName,
                'Key'    => $oldFolderPrefix,
            ]);

            return ["success" => true , "msg" => "Folder Updated Successfully"];

        } catch (S3Exception $e) {

            return ["success" => false , "error" => "Error Updating Folder: " . $e->getMessage()];
        
        }
    }

    public function uploadMedia($folderName , $fileName , $file){
        try{

            $this->s3->putObject([
                'Bucket' => $this->bucketName,
                'Key'    => $folderName."/". $fileName, 
                'SourceFile' => $file->path(), 
            ]);

        return ["success" => true , "msg" => "File Uploaded Successfully"];

        } catch (S3Exception $e) {

            return ["success" => false , "error" => "Error Updating Folder: " . $e->getMessage()];
        
        }
    }

    public function deleteMedia($file){
        try{
            $this->s3->headObject([
                'Bucket' => $this->bucketName,
                'Key'    => $file,
            ]);

            $this->s3->deleteObject([
                'Bucket' => $this->bucketName,
                'Key'    => $file,
            ]);

            return  ["success" => true , "msg" => "File Deleted Successfully"];

        } catch (S3Exception $e) {
            return ["success" => false , "error" => "Error Deleting File: No File Found On Server"];
        
        }
        
    }



}