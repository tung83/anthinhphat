<?php
class product{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',13);
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
            $item=$this->db->getOne('product','id,title,pId');
            $cate=$this->db->where('id',$item['pId'])->getOne('product_cate','id,title');
            $str.='
            <li><a href="'.myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'].'">'.$cate['title'].'</a></li>
            <li><a href="#">'.$item['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('product_cate','id,title');
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
    function product_sale(){
        $str.='
        <section class="product-sale">
            <div class="container">';
        $list=$this->db->where('active',1)->where('price_reduce',0,'<>')->orderBy('ind','ASC')->orderBy('id')->get('product',6);
        $i=1;
        foreach($list as $item){
            if($i%2==1){
                $str.='
                <div class="row">';
            }
            $title=$this->lang=='en'?$item['e_title']:$item['title'];
            $feature=$this->lang=='en'?$item['e_feature']:$item['feature'];
            $percent=100-round(($item['price_reduce']*100)/($item['price']==0?1:$item['price']),0);
            $lnk='#';
            $str.='
            <div class="col-md-6 product-sale-item">
                <div class="row">
                    <div class="col-xs-8">
                        <a href="'.$lnk.'">
                            <h2>'.$title.'</2h>
                            <p>'.common::str_cut($feature,200).'</p>
                            <p><s>'.number_format($item['price'],0,',','.').'</s><sup>VNĐ</sup></p>
                            <p><b>'.number_format($item['price_reduce'],0,',','.').'</b><sup>VNĐ</sup></p>
                        </a>
                        <a href="#" class="btn btn-default btn-ind-about"><i class="fa fa-shopping-cart"></i> '.cart.'</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="'.$lnk.'">
                            <img src="'.webPath.$this->first_image($item['id']).'" class="img-responsive"/>
                            <p class="percent">
                                <b>'.$percent.'%</b>
                                OFF
                            </p>
                        </a>
                    </div>
                </div>
            </div>';
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
        $str.='
            </div>
        </section>';
        return $str;
    }
    function ind_product(){ 
        $str.='
        <section id="ind-product">
            <div class="container">
            <h2 class="awesome-title">
                <span>
                    '.$this->title.'
                </span>
            </h2>';
        $list=$this->db->where('active',1)->where('price_reduce',0)->where('home',1)->orderBy('id')->orderBy('id')->get('product');
        $i=1;   
        foreach($list as $item){
            if($i%4==1){
                $str.='
                <div class="row">';
            }
            $title=$this->lang=='en'?$item['e_title']:$item['title'];
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $img='thumb_'.$this->first_image($item['id']);
            $cate=$this->db->where('id',$item['pId'])->getOne('product_cate','id,title');
            $str.='
            <div class="col-md-3 product-item text-center">
                <a href="'.$lnk.'">
                    <img src="'.webPath.$img.'" class="img-responsive center-block"/>
                    <h2>'.$title.'</h2>
                    <span>'.$cate['title'].'</span>
                    <p><b>'.number_format($item['price'],0,',','.').'</b><sup>VNĐ</sup></p>
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
            </div><!--/.container-->
        </section><!--/#partner-->';
        return $str;
    }
    function hot_product(){
        $this->db->reset();
        $this->db->where('active',1)->where('home',1);
        $list=$this->db->get('product',null);
        $i=1;
        foreach($list as $item){
            if($i%4==1){
                $str.='
                <div class="row">';
            }
            $str.=$this->product_item($item);
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
        return $str;
    }
    function product_item($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $img=$this->first_image($item['id']);
        $cate=$this->db->where('id',$item['pId'])->getOne('product_cate','id,title');
        $str.='
        <div class="col-md-4 wow fadeInLeft product-item text-center">
            <a href="'.$lnk.'">
                <img src="'.webPath.$img.'" class="img-responsive center-block"/>
                <h2>'.$item['title'].'</h2>
                <span>'.$cate['title'].'</span>
                <p><b>'.number_format($item['price'],0,',','.').'</b><sup>VNĐ</sup></p>
            </a>
        </div>';
        
        return $str;
    }
    function product_list_item($item,$type=1){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $img=$this->first_image($item['id']);
        if(trim($img)==='') $img='holder.js/400x300';else $img=webPath.$img;
        if($type==1){
            $str='
            <div class="col-md-12 col-sm-6 col-md-3 product-item">';    
        }else{
            $str='
            <div class="col-md-12 col-sm-6 col-md-4 product-item">';
        }        
        $str.='
        <a href="'.$lnk.'">
            <div>
                <p>'.($item['price']==0?contact:number_format($item['price'],0,',','.').' VNĐ').'</p>
                <img src="'.$img.'" class="img-responsive" />
                <p>
                    <h2>'.$item['title'].'</h2>
                    <button class="btn btn-default">'.more.'</button>
                </p>
            </div>
        </a>
        </div>';
        return $str;
    }
    function check_pId(){
        if(isset($_GET['pId'])){
            $pId=intval($_GET['pId']);
        }elseif(isset($_GET['id'])){
            $item=$this->db->where('id',intval($_GET['id']))->getOne('product','pId');
            $pId=$item['pId'];
        }else $pId=0;
        return $pId;
    }
    function category(){
        $pId=$this->check_pId();
        $list=$this->db->where('active',1)->orderBy('ind','ASC')->get('product_cate',null,'id,title');
        $str='
        <div class="row product-category">
        <div class="col-xs-12">';
        foreach($list as $item){
            if($item['id']==$pId){
                $active=' class="active"';
            }else{
                $active='';
            }
            $str.='
            <a href="'.myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-p'.$item['id'].'"'.$active.'>
                '.$item['title'].'
            </a>';
        }
        $str.='
        </div>
        </div>';
        return $str;
    }
    function product_cate(){
        $pId=$this->check_pId();
        $this->db->reset();
        if($pId>0){
            $lev=$this->db->where('id',$pId)->getOne('product_cate','lev');
            $this->db->where('pId',$pId);
        }
        $this->db->where('active',1)->orderBy('id');
        $this->db->pageLimit=pd_lim;
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $list=$this->db->paginate('product',$page);
        $count=$this->db->totalCount;
        $i=1;
        foreach($list as $item){
            if($i%3==1){
                $str.='
                <div class="row">';
            }
            $str.=$this->product_item($item);
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
        /*$pagenumber = $page;
        $totalrecords = $count;
        $pg=new Pagination(array('limit'=>1,'count'=>20,'page'=>$page,'type'=>0));
        $pg->set_url(array('def'=>'index.php','url'=>'index.php?page=[p]'));
        $str.=$pg->process();*/
        return $str; 
    }
    function product_list($pId,$type=1){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->where('active',1)->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('product',$page,'id,title,price,price_reduce');
        $str='
        <div class="row">';
        foreach($list as $item){
            $str.=$this->product_list_item($item,$type);
        }
        $str.='
        </div>';
        return $str;
    }
    function product_one(){
        $id=intval($_GET['id']);
        $this->db->where('id',$id);
        $item=$this->db->getOne('product','id,price,price_reduce,title,content,pId,feature,manual,promotion,video');
        $this->db->where('pId',$item['pId'])->where('id',$item['id'],'<>')->where('active',1)->orderBy('rand()');
        $list=$this->db->get('product',5);
        $lnk=domain.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <section id="about-us">
            <div class="container">';
        $str.='
        <div class="row product-detail clearfix">
            <div class="col-md-5">
                '.$this->product_image_show($item['id']).'
            </div>
            <div class="col-md-7">
                <article class="product-one">
                <h1>'.$item['title'].'</h1>
                <b>Đơn Giá: <em>'.number_format($item['price'],0,',','.').'<sup>VNĐ</sup></em></b>
                <!--form action="javascript:add_cart('.$item['id'].',1)">
                    <button class="btn btn-default"><i class="fa fa-shopping-cart"></i> Mua Hàng</button>
                </form-->
                <p>'.$item['feature'].'</p>
                </article>
            </div>
        </div>                   
        <div>
            <div id="tabs" class="tabs">
                <ul>
                    <li><a href="#tabs-1">HƯỚNG DẪN THUÊ XE</a></li>
                    <li><a href="#tabs-2">THỦ TỤC</a></li>
                    
                </ul>
                <div id="tabs-1">
                    <article>
                        <p>'.$item['content'].'</p>
                    </article>
                </div>
                <div id="tabs-2">
                    <article>
                        <p>'.$item['manual'].'</p>
                    </article>
                </div>
                
            </div>       
        </div>';
        if(count($list)>0){
            $str.='
            <div class="wow fadeInDown row">
                <h2 class="awesome-title">
                    <span>
                        '.$this->title.'
                    </span>
                </h2>
            </div>';
           $i=1;
            foreach($list as $item){
                if($i%3==1){
                    $str.='
                    <div class="row">';
                }
                $str.=$this->product_item($item);
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
        }      
        $str.='
            </div>
        </section>';  
        return $str;
    }
    function product_image_show($id){
        $this->db->reset();
        $list=$this->db->where('active',1)->where('pId',$id)->orderBy('ind','ASC')->orderBy('id')->get('product_image');
        $temp=$tmp='';
        foreach($list as $item){
            $temp.='
            <li>
                <a href="'.webPath.$item['img'].'" >
                    <img src="'.webPath.$item['img'].'" alt="" title="" class=""/>
                </a>
            </li>';
            $tmp.='
            <li>
                <img src="'.webPath.'thumb_'.$item['img'].'" alt="" title=""/>
            </li>';
        }
        $str.='
        <!-- Place somewhere in the <body> of your page -->
        <div id="image-slider" class="flexslider">
          <ul class="slides popup-gallery">
            '.$temp.'
          </ul>
        </div>
        <div id="carousel" class="flexslider" style="margin-top:-50px;margin-bottom:10px">
          <ul class="slides">
            '.$tmp.'
          </ul>
        </div>
        <script>
        $(window).load(function() {
          // The slider being synced must be initialized first
          $("#carousel").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 80,
            itemMargin: 5,
            asNavFor: "#image-slider"
          });
         
          $("#image-slider").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            sync: "#carousel"
          });
        });
        </script>';
        return $str;
    }
    function first_image($id){
        $this->db->reset();
        $this->db->where('active',1)->where('pId',$id)->orderBy('ind','ASC')->orderBy('id');
        $img=$this->db->getOne('product_image','img');
        return $img['img'];
    }
}
?>