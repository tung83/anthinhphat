<?php
class serv{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',5);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
            $this->title=$item['e_title'];
        }else{
            $this->view=$item['view'];
            $this->title=$item['title'];
        }
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <section class="for-breadcrumb" style="background-image:url('.selfPath.'serv_background.png)">
        <div>
        <div class="container">
        <div class="row">
            <ul class="breadcrumb clearfix">
            	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
                <li><a href="'.myWeb.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('serv','id,title,pId');
            $cate=$this->db->where('id',$item['pId'])->getOne('serv_cate','id,title');
            $str.='
            <li><a href="'.myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'].'">'.$cate['title'].'</a></li>
            <li><a href="#">'.$item['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('serv_cate','id,title');
            $str.='
            <li><a href="#">'.$cate['title'].'</a></li>';
        }
        $str.='
            </ul>
            <h3 class="page-title">'.$this->title.'</h3>
        </div>
        </div>
        </div>
        </section>';
        return $str;
    }
    function serv_item($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <div class="col-md-6 serv-item">
        <a href="'.$lnk.'" class="row">
            <div class="col-xs-4">
                <img src="'.webPath.$item['img'].'" class="img-responsive img-circle" alt="" title=""/>
            </div>
            <div class="col-xs-8">
                <h2>'.$item['title'].'</h2>
                <span>'.nl2br(common::str_cut($item['sum'],320)).'</span>
            </div>
        </a>
        </div>';
        return $str;
    }
    function check_pId(){
        if(isset($_GET['pId'])){
            $pId=intval($_GET['pId']);
        }elseif(isset($_GET['id'])){
            $item=$this->db->where('id',intval($_GET['id']))->getOne('serv','pId');
            $pId=$item['pId'];
        }else $pId=0;
        return $pId;
    }
    function serv_cate(){
        $pId=$this->check_pId();
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('serv',$page);
        $count=$this->db->totalCount;
        $str.='
        <section id="about-us">
            <div class="container">';
        if($count>0){
            $i=1;
            foreach($list as $item){
                if($i%2==1){
                    $str.='
                    <div class="row">';
                }
                $str.=$this->serv_item($item);
                if($i%2==0){
                    $str.='
                    </div>';
                }
                $i++;
            }
            if($i%2!=1){
                $str.='
                </div>';
            }
        }        
        $str.='
            </div>
        </section>';
        $pg = new Pagination();
        $pg->pagenumber = $page;
        $pg->pagesize = limit;
        $pg->totalrecords = $count;
        $pg->showfirst = true;
        $pg->showlast = true;
        $pg->paginationcss = "pagination-large";
        $pg->paginationstyle = 1; // 1: advance, 0: normal
        if($pId==0){
            $pg->defaultUrl = myWeb.$this->view;
            $pg->paginationUrl = myWeb.'[p]/'.$this->view;    
        }else{
            $cate=$this->db->where('id',$pId)->getOne('serv_cate','id,title');            
            $pg->defaultUrl = myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $pg->paginationUrl = myWeb.$this->view.'/[p]/'.common::slug($cate['title']).'-p'.$cate['id'];
        }
        $str.= '<div class="pagination pagination-centered">'.$pg->process().'</div>';
        return $str;
    }
    function serv_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('serv');
        $str='
        <section id="about-us">
        <div class="container">
			<div class="wow fadeInDown row">
            <div class="col-md-12">
                <article>
                    <h1>'.$item['title'].'</h1>
                    <p>'.$item['content'].'</p>
                </article>
			</div>
            </div>
        </div>
        </section>';
        return $str;
    }
    function ind_serv(){
        $this->db->where('active',1)->orderBy('id');
        $list=$this->db->get('serv',null,'id,title,sum,img');
        $str='
        <section class="ind-serv">
        <div class="container">';
        $i=1;
        foreach($list as $item){
            if($i%3==1){
                $str.='
                <div class="row">';
            }
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $str.='
            <div class="col-md-4 ind-serv-item wow fadeInRight">
                <a href="'.$lnk.'" class="row">
                    <div class="col-xs-4">
                        <img src="'.webPath.$item['img'].'" class="img-responsive img-circle"/>
                    </div>
                    <div class="col-xs-8">
                        <h2>'.$item['title'].'</h2>
                        <p>'.common::str_cut($item['sum'],120).'</p>
                    </div>
                </a>
            </div>';
            
            if($i%3==0){
                $str.='
                </div>';
            }
            $i++;
        }
        if($i%3!=1){
            $str.='
            </div>';
        }
        $str.='
        </div>
        </section>';
        return $str;
    }
    function one_ind_serv($id){
        $this->db->reset();
        $this->db->where('id',$id);
        $item=$this->db->getOne('serv','id,img,title,sum');
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="ind_serv">
            <a href="'.$lnk.'">
                <img src="'.webPath.$item['img'].'" alt="" title="'.$item['title'].'"/>
                <h2>'.$item['title'].'</h2>
                <span>'.common::str_cut($item['sum'],120).'</span>
            </a>
        </div>';
        return $str;
    }
    function product_image_first($db,$pId){
        $db->where('active',1)->where('pId',$pId);
        $item=$db->getOne('product_image','img');
        return $item['img'];
    }

}
?>
