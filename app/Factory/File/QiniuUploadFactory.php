<?php


namespace App\Factory\File;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class QiniuUploadFactory
{
    protected $auth;

    protected $bucket;

    public function __construct()
    {
        $accessKey = env('QINIU_ACCESS_KEY', '');
        $secretKey = env('QINIU_SECRET_KEY');
        $this->bucket = env('QINIU_BUCKET', '');
        $this->auth = new Auth($accessKey, $secretKey);
    }

    /**
     * @param string $key
     * @return string
     */
    public function getToken($key = null) {
        return $this->auth->uploadToken($this->bucket, $key);
    }

    /**
     * @param string $key
     * @param string $filePath
     * @return mixed
     * @throws \Exception
     */
    public function uploadFile(string $key, string $filePath) {
        $upload = new UploadManager();
        $token  = $this->getToken($key);
        list($res, $err) = $upload->putFile($token, $key, $filePath);
        if ($err) {
            throw new \Exception($err);
        }
        return $res;
    }

    /**
     * @param string $key
     * @param $file
     * @return mixed
     * @throws \Exception
     */
    public function putFile(string $key, $file) {
        $upload = new UploadManager();
        $token  = $this->getToken($key);
        list($res, $err) = $upload->put($token, $key, $file);
        if ($err) {
            throw new \Exception($err);
        }
        return $res;
    }
}