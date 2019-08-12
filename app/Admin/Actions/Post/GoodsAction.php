<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class GoodsAction extends RowAction
{
    public $name = 'sku管理';

//    public function handle(Model $model)
//    {
//        // $model ...
//
//        return $this->response()->success('Success message.')->refresh();
//    }
    public function href()
    {
        $key = $this->getKey();
//        echo $key;die;
        return "/admin/goods/sku/".$key;
//        return "/admin/skus/".$key;
    }

}