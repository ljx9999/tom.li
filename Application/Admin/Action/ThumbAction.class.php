<?php
namespace Admin\Action;
/**
 * Created by JetBrains PhpStorm.
 * User: lijixin
 *  
 */

class ThumbAction extends AdminBaseAction
{


    /**
     *
     */
    public function upload() {
        import('Org.Net.UploadFile');
        //导入上传类
        $upload = new \Org\Net\UploadFile();
        //设置上传文件大小
        $upload->maxSize            = 3292200;
        //设置上传文件类型
        $upload->allowExts          = explode(',', 'jpg,gif,png,jpeg');
        //设置附件上传目录
        $upload->savePath = dirname($_SERVER['SCRIPT_FILENAME']).'/Uploads/'.date('Ymd').'/';
        // 设置附件上传目录
        $upload->saveRule = time().'_'.mt_rand();
        //设置需要生成缩略图，仅对图像文件有效
        $upload->thumb              = true;
        // 设置引用图片类库包路径
        $upload->imageClassPath     = '@.ORG.Image';
        //设置需要生成缩略图的文件后缀
        $upload->thumbPrefix        = 'm_';  //生产2张缩略图
        //设置缩略图最大宽度
        $upload->thumbMaxWidth      = '400,100';
        //设置缩略图最大高度
        $upload->thumbMaxHeight     = '400,100';
        //设置上传文件规则
        $upload->saveRule           = 'uniqid';
        //删除原图
        $upload->thumbRemoveOrigin  = false;
        if (!$upload->upload()) {
            //捕获上传异常

            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();

            //import('Org.Util.Image');
            //给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
            $Image = new \Org\Util\Image();
            $Image->water($uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename'], dirname($_SERVER['SCRIPT_FILENAME']).'/'.'Tpl/Public/Images/logo.png');
            $_POST['image'] = $uploadList[0]['savename'];
        }
        $uploadList[0]['savepath'] = date('Ymd').'/';
        $Img['data'] = $uploadList[0];
        $this->ajaxReturn($Img,'JSON',1);
    }

    public function uploadMultiple()
    {

        $date = date('Ymd');

        import('Org.Net.UploadFile');
        //导入上传类
        $upload = new \Org\Net\UploadFile();
        //设置上传文件大小
        $upload->maxSize            = 3292200;
        //设置上传文件类型
        $upload->allowExts          = explode(',', 'jpg,gif,png,jpeg');
        //设置附件上传目录
        $upload->savePath = dirname($_SERVER['SCRIPT_FILENAME']).'/Uploads/'.$date.'/';
        // 设置附件上传目录
        $upload->saveRule = time().'_'.mt_rand();
        //设置需要生成缩略图，仅对图像文件有效
        $upload->thumb              = true;
        // 设置引用图片类库包路径
        $upload->imageClassPath     = '@.ORG.Image';
        //设置需要生成缩略图的文件后缀
        $upload->thumbPrefix        = 'm_';  //生产2张缩略图
        //设置缩略图最大宽度
        $upload->thumbMaxWidth      = '100';
        //设置缩略图最大高度
        $upload->thumbMaxHeight     = '100';
        //设置上传文件规则
        $upload->saveRule           = 'uniqid';
        //删除原图
        $upload->thumbRemoveOrigin  = false;
        if (!$upload->upload()) {
            //捕获上传异常

            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();

            //import('@.Org.Util.Image');
//            //给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
//            Image::water($uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename'], APP_PATH.'Tpl/Public/Images/logo.png');
//            $_POST['image'] = $uploadList[0]['savename'];
        }

        foreach ( $uploadList as & $file)
        {
            $file['savepath'] = $date;
            $this->saveImage($file);
        }
      //  $uploadList[0]['savepath'] = date('Ymd').'/';
        $Img['data'] = $uploadList;
        $this->ajaxReturn( $Img,'JSON',1);
    }


    /**
     * 动态更新图片的Title
     */
    public function ajaxSaveImageTitle()
    {
        $id = $this->_post('id');
        $title = $this->_post('title');

        $image = M('image');
        $image->where(array("id"=>$id))->save(array("title" => $title));

        $this->success(0);
    }
    /**
     * 动态更新图片的Order
     */
    public function ajaxSaveImageOrder()
    {
        $id = $this->_post('id');
        $order = $this->_post('order');

        $image = M('image');
        $image->where(array("id"=>$id))->save(array("order" => $order));

        $this->success(0);
    }

    /**
     * 删除缩略图
     */
    public function ajaxDeleteImageAct()
    {
        $id = $this->_get('id');
        $image = M('image');
        $image->delete($id);
        $this->success(0);
    }

    /**
     * @param $file
     * 保存图片信息到数据库
     */
    private function saveImage( &$file )
    {
        $imageData = array();
        $imageData['thumb'] =  $file['savepath']."/m_".$file['savename'];
        $imageData['image_src'] = $file['savepath']."/".$file['savename'];

        $image = M('image');
        $image->add($imageData);
        $id = $image->getLastInsID();
        $file['image_id'] = $id;
    }
}