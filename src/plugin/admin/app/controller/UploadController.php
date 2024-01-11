<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2024-01-09
 * Time: 13:47:58
 * Info:
 */

namespace plugin\admin\app\controller;

use support\Container;
use support\Request;
use yzh52521\Filesystem\Facade\Filesystem;
use plugin\admin\app\model\UploadFile;

class UploadController
{

    /**
     * 无需登录及鉴权的方法
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 不需要权限的方法
     *
     * @var string[]
     */
    protected $noNeedAuth = ['upload'];

    /**
     * @var Upload
     */
    protected $model = null;

    /**
     * 只返回当前管理员数据
     * @var string
     */
    protected $dataLimit = 'personal';

    /**
     * 上传附件
     *
     * @param $editorType   编辑器类型
     * @param $isBase64     是否base64图片
     *
     * @return void
     */
    public function upload($editorType = '', $isBase64 = false)
    {
        $request     = request();
        $upload_mode = get_config('upload_mode');

        if ($isBase64 === true) {
            //涂鸦上传（base64）
            $res = $this->upBase64($request->input('file'));

            return $res;
        } else {
            foreach ($request->file() as $key => $spl_file) {
                $groupId = $request->post('group_id', 0);
                if ($groupId == 'undefined') {
                    $groupId = 0;
                }
                $savePath   = $request->post('save_path', '');
                $editorType = $request->get('editor_type', '');
                if ($groupId == -1) {
                    $groupId = 0;
                }
                //获取最大上传限制
                $maxUpSize = config('server.max_package_size') - 100;
                if ($spl_file && $spl_file->isValid()) {
                    $fileSize = $spl_file->getSize();
                    if ($fileSize > $maxUpSize) {
                        return json(['code' => 0, 'msg' => '超出最大上传限制，请处理后再上传']);
                    }
                    $getMime   = $spl_file->getUploadMineType();
                    $extension = $spl_file->getUploadExtension();
                    $mime_type = $spl_file->getUploadMimeType();
                    if (empty($savePath)) {
                        if (strstr($getMime, 'image')) {
                            $fileMime = "images";
                        } else {
                            $fileMime = "files";
                        }
                        $upload_types = $this->_get_upload_types($extension, $fileMime);
                        if ( ! $upload_types) {
                            return error_json('不允许上传 '.$extension.' 格式的文件');
                        }
                    } else {
                        $fileMime = $savePath;
                    }
                    try {
                        $path = Filesystem::disk($upload_mode)->putFile('pic', $spl_file);
                    } catch (\Exception $e) {
                        return json(['code' => 0, 'msg' => $e->getMessage()]);
                    }
                    $fileUrl     = Filesystem::url($path);
                    $baseFileUrl = public_path().$fileUrl;
                    $fileUrl     = str_replace("\\", "/", $fileUrl);
                    $explodePath = explode("/", $fileUrl);
                    $fileName    = end($explodePath);
                    $image_with  = $image_height = 0;

                    if ($img_info = getimagesize($baseFileUrl)) {
                        [$image_with, $image_height] = $img_info;
                        $mime_type = $img_info['mime'];
                    }

                    $param = [
                        'storage'      => $upload_mode,
                        'group_id'     => $groupId,
                        'file_url'     => $fileUrl,
                        'file_name'    => $fileName,
                        'file_size'    => $fileSize,
                        'file_type'    => $mime_type,
                        'image_with'   => $image_with,
                        'image_height' => $image_height,
                        'extension'    => $extension
                    ];
                    if ($fileMime == 'images') {
                        //水印-图片
                        $this->_add_water($fileUrl);
                        //写入数据库
                        $this->_att_write($param);
                    }
                    if ($editorType == 'ueditor') {
                        return json(['state' => 'SUCCESS', 'url' => $fileUrl]);
                    } else {
                        return json(['code' => 200, 'msg' => '上传成功', 'url' => $fileUrl]);
                    }
                } else {
                    return json(['code' => 0, 'msg' => '参数错误']);
                }
            }

        }
    }

    /**
     * 写入数据库
     *
     * @param $param
     *
     * @return void
     */
    private function _att_write($param)
    {
        UploadFile::create($param);
    }

    /**
     * 添加水印
     *
     * @param $fileName
     *
     * @return void
     */
    private function _add_water($fileName)
    {
        if ( ! get_config('watermark_enable')) {
            return;
        }

    }

    /**
     * 允许上传类型
     *
     * @param $ext
     * @param $type
     *
     * @return bool
     */
    private function _get_upload_types($ext, $type = 'images')
    {
        if ($type == 'images') {
            $arr = explode(',', get_config('upload_types_image'));
        } else {
            $arr = explode(',', get_config('upload_types_file'));
        }
        if ( ! in_array($ext, $arr)) {
            return false;
        }

        return true;

    }

    /**
     * 处理base64编码的图片上传
     * @return mixed
     */
    private function upBase64($fileField)
    {
        $base64_image_content = $fileField;
        if (empty($base64_image_content)) {
            return false;
        }
        //合成图片的base64编码成
        $base64_image_content = "data:image/png;base64,{$base64_image_content}";
        //匹配出图片的信息
        $match = preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result);
        if ( ! $match) {
            return false;
        }

        //解码图片内容
        $base64_image = str_replace($result[1], '', $base64_image_content);
        $file_content = base64_decode($base64_image);
        $file_type    = $result[2];

        //如果没指定目录,则保存在当前目录下
        $pathTime = date('Ymd');
        $filePath = "/uploads/crawl/".$pathTime."/";
        $path     = public_path().$filePath;
        if ( ! is_dir($path)) {
            @mkdir($path, 0777, true);
        }
        $file_name = time().".{$file_type}";

        $new_file = $path.$file_name;

        if (file_exists($new_file)) {
            //有同名文件删除
            @unlink($new_file);
        }
        if (file_put_contents($new_file, $file_content)) {
            return json_encode(['state' => 'SUCCESS', 'url' => $filePath.$file_name], JSON_UNESCAPED_UNICODE);
        }

        return false;
    }

}
