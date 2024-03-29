<?php

namespace App\Admin\Controllers;

use App\Model\Goods;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Post\GoodsAction;
class PostController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\Goods';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Goods);

        $grid->column('id', __('Id'));
        $grid->column('goods_name', __('商品名'));
        $grid->column('self_price', __('售价'));
        $grid->column('market_price', __('定价'));
        $grid->column('goods_num', __('商品库存'));
        $grid->column('sell_num', __('卖出数量'));
        $grid->column('goods_desc', __('商品介绍'));
        $grid->column('is_up', __('是否上架'));
        $grid->column('is_new', __('是否新品'));
        $grid->column('goods_sn', __('商品编号'));
//        $grid->column('goods_imgs', __('Goods imgs'));
        $grid->column('cate_id', __('Cate id'));
        $grid->column('brand_id', __('商家id'));
        $grid->column('create_time', __('Create time'));

        $grid->actions(function($actions){
            $actions->add(new GoodsAction());
        });

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
        $show = new Show(Goods::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('goods_name', __('Goods name'));
        $show->field('self_price', __('Self price'));
        $show->field('market_price', __('Market price'));
        $show->field('goods_num', __('Goods num'));
        $show->field('sell_num', __('Sell num'));
        $show->field('goods_desc', __('Goods desc'));
        $show->field('is_up', __('Is up'));
        $show->field('is_new', __('Is new'));
        $show->field('goods_img', __('Goods img'));
        $show->field('goods_imgs', __('Goods imgs'));
        $show->field('cate_id', __('Cate id'));
        $show->field('brand_id', __('Brand id'));
        $show->field('create_time', __('Create time'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Goods);

        $form->text('goods_name', __('Goods name'));
        $form->number('self_price', __('Self price'));
        $form->number('market_price', __('Market price'));
        $form->number('goods_num', __('Goods num'));
        $form->number('sell_num', __('Sell num'));
        $form->textarea('goods_desc', __('Goods desc'));
        $form->switch('is_up', __('Is up'));
        $form->switch('is_new', __('Is new'))->default(2);
        $form->text('goods_img', __('Goods img'));
        $form->text('goods_imgs', __('Goods imgs'));
        $form->number('cate_id', __('Cate id'));
        $form->number('brand_id', __('Brand id'));
        $form->number('create_time', __('Create time'));

        return $form;
    }
}
