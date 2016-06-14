<?php

namespace Admin\Controller;

class ArticleController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->model = 'article';
        $this->model_view = 'ArticleView';
        $this->key = 'doc_id';
        $this->title_index = '文章列表';
        $this->title_details = '文章详情';
    }

    public function index()
    {
        parent::cms_index();
    }

    public function addHandle()
    {
        $oop = M($this->model);
        $oop->create();

        if (!empty($_FILES['file']['tmp_name'])) {
            $result = parent::uploadfile($exts = array('jpg', 'gif', 'png', 'jpeg'), $thumb = true);
            if (!$result['status']) {
                $this->error($result['info']);
            }
            $oop->doc_dir = $result['info']['file']['savepath'];
            $oop->doc_img = $result['info']['file']['savename'];
        }
        //dump(I());
        //dump($result);exit;
        try {
            $oop->doc_property = serialize(I('doc_property'));
            if ($oop->add()) {
                $this->success(L('add_success'));
            } else {
                $this->error(L('add_failed'));
            }
        } catch (\Exception $e) {
            //dump($e);exit;
            if ($e->getCode() == 23000) {
                $this->error(L('unique_error'));
            }
        }
    }

    public function updateHandle()
    {
        $oop = M($this->model);
        $oop->create();

        if (!empty($_FILES['file']['tmp_name'])) {
            $result = parent::uploadfile($exts = array('jpg', 'gif', 'png', 'jpeg'), $thumb = true);
            if (!$result['status']) {
                $this->error($result['info']);
            }
            $oop->doc_dir = $result['info']['file']['savepath'];
            $oop->doc_img = $result['info']['file']['savename'];
        }
        //dump(I());
        //dump($result);exit;
        try {
            $oop->doc_property = serialize(I('doc_property'));
            if ($oop->save()) {
                $this->success(L('update_success'));
            } else {
                $this->error(L('update_failed'));
            }
        } catch (\Exception $e) {
            //dump($e);exit;
            if ($e->getCode() == 23000) {
                $this->error(L('unique_error'));
            }
        }
    }
}
