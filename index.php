<?php include_once 'function.php';?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="author" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <?=pageHeader($view,$db)?>  
    <link rel="icon" type="image/png" href="<?=selfPath?>logo.png"/>   
    <?=common::basic_css()?> 
    <?=common::basic_js()?>
</head>
<body>
    <?=menu($db,$lang,$view)?>
    <?php
    switch($view){
        case 'danh-sach-xe':
            echo product($db);
            break;
        case 'dich-vu':
            echo serv($db);
            break;
        case 'project':
        case 'du-an':
            echo project($db,$lang);
            break;
        case 'recruitment':
        case 'tuyen-dung':
            echo career($db,$lang);
            break;
        case 'news-event':
        case 'tin-tuc-su-kien':
            echo news($db,$lang);
            break;
        case 'about-us':
        case 'gioi-thieu':
            echo about($db,$lang);
            break;
        case 'lien-he':
        case 'contact':
            echo contact($db,$lang);
            break;
        case 'search':
        case 'tim-kiem':
            echo search($db,$lang);
            break;
        default:
            echo home($db,$lang);
            break;
    }
    if($view=='lien-he'||$view=='trang-chu'){
        echo '
        <section class="gmap">
            '.$basic_config['gmap_script'].'
        </section>';
    }
    ?>
    
    <footer>
        <div class="container">
            <div class="row footer">
                <div class="col-md-12">
                    <?=foot_menu($db,$lang,$view)?>
                </div>
                <div class="col-md-6">
                    <?=common::qtext($db,4)?>
                </div>
                <div class="col-md-5">
                    <div class="fb-page" 
                      data-href="https://www.facebook.com/Du-L%E1%BB%8Bch-An-Th%E1%BB%8Bnh-Ph%C3%A1t-1862088960691992/?fref=ts"
                      data-width="380" 
                      data-hide-cover="false"
                      data-show-facepile="true" 
                      data-show-posts="false"></div>
                </div>
                <div class="col-md-1">
                    <ul class="soc">
                        <li><a class="soc-facebook" href="#"></a></li>
                        <li><a class="soc-twitter" href="#"></a></li>
                        <li><a class="soc-googleplus" href="#"></a></li>
                        <li><a class="soc-rss soc-icon-last" href="#"></a></li>
                    </ul>  
                </div>
            </div>
            <div class="row">
                
            </div>
        </div>
        <section class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        Copyright © 2016 <b>AN THỊNH PHÁT.</b>. All Rights Reserved. Designed by <a>PSmedia.vn</a>
                    </div>
                </div>
            </div>
        </section>
    </footer>
<div class="coccoc-alo-phone coccoc-alo-green coccoc-alo-show" id="coccoc-alo-phoneIcon" style="left: 0px; bottom: 0px;">
	<div class="coccoc-alo-ph-circle"></div>
	<div class="coccoc-alo-ph-circle-fill"></div>
	<div class="coccoc-alo-ph-img-circle">
        <a href="tel:<?=common::remove_format_text(common::qtext($db,2))?>"><img src="/file/self/phone-ring.png" alt=""/></a>
    </div>
</div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.7&appId=1526299550957309";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script type="text/javascript">
(function(d,s,id){var z=d.createElement(s);z.type="text/javascript";z.id=id;z.async=true;z.src="//static.zotabox.com/e/2/e20da095c186ca083e0b0d3bdb8991c3/widgets.js";var sz=d.getElementsByTagName(s)[0];sz.parentNode.insertBefore(z,sz)}(document,"script","zb-embed-code"));
</script>
</body>
</html>