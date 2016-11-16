<?php
class news{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',8);
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
        <section class="for-breadcrumb" style="background:#4bcfc0 url('.selfPath.'news_background.png) no-repeat right center">
        <div>
        <div class="container">
        <div class="row">
            <ul class="breadcrumb clearfix">
            	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
                <li><a href="'.myWeb.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('serv','id,title,pId');
            $cate=$this->db->where('id',$item['pId'])->getOne('news_cate','id,title');
            $str.='
            <li><a href="'.myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'].'">'.$cate['title'].'</a></li>
            <li><a href="#">'.$item['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('news_cate','id,title');
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
    function ind_news(){
        $this->db->reset();
        $list=$this->db->where('active',1)->orderBy('id')->get('news',3);
        $str.='
        <section id="ind-news">
            <div class="container">
            <h2 class="awesome-title">
                <span>
                    '.$this->title.'
                </span>
            </h2>';
        $i=1;
        foreach($list as $item){
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $img=webPath.$item['img'];
            if($img=='') $img='holder.js/126x100';
            if($i%4==1){
                $str.='
                <div class="row">';
            }
            $str.='
            <div class="col-md-3 ind-news-item wow fadeInLeft">
                <a href="'.$lnk.'">                    
                    <img src="'.$img.'" alt="'.$item['title'].'" class="img-responsive center-block"/>                                        
                    <h2>'.common::str_cut($item['title'],30).'</h2>
                    <span>'.nl2br(common::str_cut($item['sum'],160)).'</span>
                </a>
            </div>';
            if($i%4==0){
                $str.='
                </div>';
            }
            $i++;   
        }
        if($i%4!=1){
            $str.='
            </div>';
        }
        $str.='
            <p class="text-center more">
                <a href="'.myWeb.'/'.$this->view.'" class="btn btn-default">'.all.'</a>
            </p>
            </div>
        </section>';
        return $str;
    }
    function news_item($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <a href="'.$lnk.'" class="news-item">
        <div class="row">
            <div class="col-xs-3">
                <img src="'.webPath.$item['img'].'" class="img-responsive img-thumbnail" alt="" title=""/>
            </div>
            <div class="col-xs-9">
                <h2>'.$item['title'].'</h2>
                <span>'.nl2br(common::str_cut($item['sum'],620)).'</span>
            </div>
        </div>
        </a>';
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
    function news_cate(){
        $pId=$this->check_pId();
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('news',$page);
        $count=$this->db->totalCount;
        $str.='
        <section id="about-us">
            <div class="container">';
        if($count>0){
            foreach($list as $item){
                $str.=$this->news_item($item);
            }
        }        
        $str.='
            </div>
        </section>';
        $pg=new Pagination(array('limit'=>limit,'count'=>$count,'page'=>$page,'type'=>0));        
        if($pId==0){
            $pg->set_url(array('def'=>myWeb.$this->view,'url'=>myWeb.'[p]/'.$this->view));
        }else{
            $cate=$this->db->where('id',$pId)->getOne('news_cate','id,title');       
            $pg->defaultUrl = myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $pg->paginationUrl = myWeb.$this->view.'/[p]/'.common::slug($cate['title']).'-p'.$cate['id'];
        }
        $str.= '<div class="pagination-centered">'.$pg->process().'</div>';
        return $str;
    }
    function news_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('news');
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
    
    function one_ind_news($id){
        $this->db->reset();
        $this->db->where('id',$id);
        $item=$this->db->getOne('news','id,img,title,sum');
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="ind_news">
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
