<?php
class contact{
    private $db,$view,$lang,$title,$basic_config;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',12);
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
        <section class="for-breadcrumb" style="background-image:url('.selfPath.'contact_background.png);background-repeat:no-repeat;background-position:top right">
        <div>
        <div class="container">
        <div class="row">
            <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.$this->lang.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'">'.$this->title.'</a></li>';
        $str.='
            </ul>
            <h3 class="page-title">'.$this->title.'</h3>
        </div>
        </div>
        </div>
        </section>';
        return $str;
    }
    function contact_insert(){
        $this->db->reset();
        if(isset($_POST['contact_send'])){
            $name=htmlspecialchars($_POST['name']);
            $adds=htmlspecialchars($_POST['adds']);
            $phone=htmlspecialchars($_POST['phone']);
            $email=htmlspecialchars($_POST['email']);
            $subject=htmlspecialchars($_POST['subject']);
            $content=htmlspecialchars($_POST['content']);
            $insert=array(
                'name'=>$name,'adds'=>$adds,'phone'=>$phone,
                'email'=>$email,'subject'=>$subject,'content'=>$content,
                'dates'=>date("Y-m-d H:i:s")
            );
            try{
                $this->send_mail($insert);
                $this->db->insert('contact',$insert);                
                //header('Location:'.$_SERVER['REQUEST_URI']);
                echo '<script>alert("Thông tin của bạn đã được gửi đi, BQT sẽ phản hồi sớm nhất có thể, Xin cám ơn!");
                </script>';
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }
    }
    function contact(){
        $basic_config=$this->db->getOne('basic_config');
        $this->contact_insert();
        $this->db->reset();
        $item=$this->db->where('id',3)->getOne('qtext','content');
        $str.='    
        <section id="contact-page">
            <div class="container">
                <div class="row contact-wrap"> 
                    <div class="status alert alert-success" style="display: none"></div>
                    <form data-toggle="validator" role="form" class="contact-form" name="contact-form" method="post" action="">
                        <div class="col-md-12 contact-note">
                            Cảm ơn Quý khách đã truy cập vào website. Mọi thông tin chi tiết xin vui lòng liên hệ:
                        </div>
                        <div class="col-md-6">
                            <p>
                                <img src="'.selfPath.'contact.png" class="img-responsive" alt="" title=""/>
                            </p>    
                            <p>
                                '.common::qtext($this->db,3).'
                            </p>       
                        </div>
                        <div class="col-md-6">
                            <p>
                            <em>
                            <strong>Chú ý:</strong> Dấu (*) các trường bắt buộc phải nhập vào. Quý vị có thể gõ chữ tiếng Việt không dấu hoặc chữ tiếng Việt có dấu theo chuẩn UNICODE (UTF-8).
                            </em> 
                            </p>
                            <div class="form-group">
                                <label>Họ Tên *</label>
                                <input type="text" name="name" class="form-control" required/>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control" required/>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label>Điện Thoại*</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>   
                            <div class="form-group">
                                <label>Địa Chỉ*</label>
                                <input type="text" name="adds" class="form-control" required>
                            </div>      
                            <div class="form-group">
                                <label>Chủ Đề *</label>
                                <input type="text" name="subject" class="form-control" required/>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label>Nội Dung Tin Nhắn *</label>
                                <textarea name="content" id="content" required class="form-control" rows="8"></textarea>
                                <div class="help-block with-errors"></div>
                            </div>                        
                            <div class="form-group">
                                <button type="submit" name="contact_send" class="btn btn-primary btn-md btn-custom">
                                    Gửi Tin
                                </button>
                                <button type="reset" name="reset" class="btn btn-primary btn-md btn-custom">
                                    Xóa
                                </button>
                            </div>
                        </div>
                    </form> 
                </div><!--/.row-->
            </div><!--/.container-->
        </section><!--/#contact-page-->';
        return $str;
    }
    function send_mail($item){
        $basic_config=$this->db->getOne('basic_config');      
      
        //Create a new PHPMailer instance
        include_once phpLib.'PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail        
        //Whether to use SMTP authentication
        //$mail->SMTPDebug = 3;
        //Ask for HTML-friendly debug output
        //$mail->Debugoutput = 'html';
        //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true;
        $mail->Host = $basic_config['smtp_server'];
        $mail->Port = $basic_config['smtp_port']; // or 587
        $mail->IsHTML(true);
        $mail->Username = $basic_config['smtp_user'];
        $mail->Password = $basic_config['smtp_pwd'];
        $mail->SetFrom($basic_config['smtp_user'], $basic_config['smtp_sender_name']);
        $mail->AddAddress($basic_config['smtp_receiver']);
        $mail->SMTPAutoTLS = false;
        $mail->CharSet = 'UTF-8';
        $mail->Subject =  'Khách hàng liên hệ gửi từ website';        
        
        $mail->Body = '
        <html>
        <head>
        	<title>'.$mail->Subject.'</title>
        </head>
        <body>
        	<p>Full Name: '.$item['name'].'</p>
        	
        	<p>Address: '.$item['adds'].'</p>
        	<p>Phone: '.$item['phone'].'</p>
        	
        	<p>Email: '.$item['email'].'</p>
                <p>Tiêu Đề: '.$item['subject'].'</p>
        	<p>Content: '.nl2br($item['content']).'</p>
        </body>
        </html>';
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
?>
