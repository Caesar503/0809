<?php

namespace App\Admin\Controllers;

use App\Model\Sku;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;

class SkuController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\Sku';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($id)
    {
        $grid = new Grid(new Sku);

        $grid->model()->where('goods_id',$id);

//        $grid->column('id', __('Id'));
        $grid->column('id', __('商品id'));
        $grid->column('goods_sn', __('商品编号'));
        $grid->column('sku', __('Sku'));
        $grid->column('desc', __('简介'));
        $grid->column('updated_at', __('最后一次修改时间'));
        $grid->column('created_at', __('第一次提交时间'));
        $grid->column('price0', __('定价'));
        $grid->column('price', __('售价'));
        $grid->column('store', __('商家id'));
        $grid->column('is_onsale', __('是否上架'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Sku::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('goods_id', __('Goods id'));
        $show->field('goods_sn', __('Goods sn'));
        $show->field('sku', __('Sku'));
        $show->field('desc', __('Desc'));
        $show->field('updated_at', __('Updeta at'));
        $show->field('created_at', __('Created at'));
        $show->field('price0', __('Price0'));
        $show->field('price', __('Price'));
        $show->field('store', __('Store'));
        $show->field('is_onsale', __('Is onsale'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Sku);

//        $form->number('id', __('Goods id'));
        $form->text('goods_sn', __('Goods sn'));
        $form->text('sku', __('Sku'));
        $form->text('desc', __('Desc'));
        $form->datetime('updated_at', __('Updeta at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('created_at', __('Updeta at'))->default(date('Y-m-d H:i:s'));
        $form->number('price0', __('Price0'));
        $form->number('price', __('Price'));
        $form->text('store', __('库存'));
        $form->number('is_onsale', __('Is onsale'));

        return $form;
    }
}
