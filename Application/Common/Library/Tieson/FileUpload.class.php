<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 嘉懿
 * Date: 13-9-5
 * Time: 上午10:14
 *  
 */
namespace Common\Library\Tieson;
    class FileUpload {

        /**
         * @param $post 页面POST过来的信息，包括image的ID
         * @param $pid 产品的ID
         * @return int
         */
        public function saveImage( $post,$pid ){

            $imageIds =  join( $post['images'] , "," );
            $image = M('image');
            $image->where(array( "id" => $imageIds ))->save(array( "p_id" => $pid ));

            return 0;
        }

    }
?>