<html>
    <head>
        <title>贫穷网</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta name="description" content="贫穷网" />
        <meta name="keywords" content="soxft,没钱,贫穷,xcsot" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <!--<script src="http://www.lmlblog.com/winter/templets/xq/js/snowy.js"></script>
        <script src="http://www.lmlblog.com/blog/14/js/Snow.js"></script>
        <style type="text/css">
        .snow-container{
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            pointer-events:none;
            z-index:100001;}
        </style>
        <div class="snow-container"></div>-->
    </head>
    <body style='max-width:90%;transform:translate(5%,20px);background-color:#F5F5F5;'>
            <div class="jumbotron" style='transform:translateY(3%);'>
                <div style='max-width:84%;transform:translate(8%);'>
                    <h1>贫穷网</h1>
                    <p>贫穷限制了我的想象力，创造力，巧克力。希望您帮我摆脱贫穷。</p>
                </div>
            </div>
            <div style='Height:5px'></div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                         <h3 class="panel-title">贫穷网</h3>
                    </div>
                     <div style='Height:30px'></div>
                    <div class="panel-body" style='max-width:90%;transform:translateX(5%);'>
                        <div id='notice' ></div>
                        <label for="basic-url">扶贫金额</label>
                        <div class="input-group"> <span class="input-group-addon" id="basic-addon3">￥</span>
                            <input id='money' type="text" placeholder="10" class="form-control" id="basic-url" aria-describedby="basic-addon1">
                        </div>
                        <div style='Height:5px'></div>
                        <label for="basic-url">扶贫留言</label>
                        <div class="input-group"> <span class="input-group-addon" id="basic-addon1"></span>
                            <input id='comment' type="text" class="form-control" placeholder="加油" aria-describedby="basic-addon1">
                        </div>
                        <div style='Height:10px'></div>
                            <div class="btn-group" role="group">
                                <button onclick='go()' id='button' type="button" class="btn btn-default">立即扶贫</button>
                            </div>
                    </div>
                     <div style='Height:30px'></div>
                </div>
                <div style='Height:5px'></div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                         <h3 class="panel-title">扶贫记录</h3> <!--(>=1元的最近5笔)-->
                    </div>
                     <div style='Height:30px'></div>
                    <div class="panel-body" style='max-width:90%;transform:translateX(5%);'>
                        <?php 
                            require_once 'config.php';
                            $arr = mysqli_query($conn,"SELECT * FROM `order` ORDER BY `time` DESC");
                            while($row=mysqli_fetch_object($arr))
                            {
                                $time = date('Y-m-d H:i:s',$row->time);
                                $comment = htmlspecialchars($row->comment);
                                
                                $ip = $row->ip;
                                $len = strlen($ip);
                                $star = '';
                                for($i = 0;$i<=$len-4;$i++)
                                {
                                    $star .= '*';
                                }
                                $ip = substr($ip,0,5) . $star;
                                
                                $i = 0;
                                echo "<div class=\"panel panel-default\">
                                          <div class=\"panel-body\">
                                            来自  $ip 的用户留言「 $comment 」 
                                          </div>
                                          <div class=\"panel-footer\">$row->money 元 | $time</div>
                                        </div>";
                                $i++;
                                if($i>4)
                                {
                                    break;
                                }
                            }
                        
                        ?>    
                    </div>
                     <div style='Height:30px'></div>
                </div>
    </body>
    <script>
    function go()
    {
        $('#button').text('Loading...');
        $('#button').attr('disabled',true);
        $.ajax({
          method: 'POST',
          url: './api.php',
          timeout: 10000,
          data: {
            'money': $('#money').val(),
            'comment': $('#comment').val()
          },
          success:function(data)
          {
              data = eval('(' + data + ')');
              //console.log(data);
              if(data[0] == '200'){
                    $('#notice').html('<div class="alert alert-success" role="alert">处理成功,跳转中...</div>')
                    setTimeout(function(){ window.location.href='pay.php' },1000);
              }else{
                    $('#notice').html('<div class="alert alert-danger" role="alert">' + data[1] + '</div>')
              }
          },
          complete:function(xhr,status){
            if(status == 'timeout')
            {
              $('#notice').html('<div class="alert alert-danger" role="alert">请求超时...</div>')
            }
            $('#button').text('立即扶贫');
            $('#button').attr('disabled',false);
          }
        });
    }
    </script>
    <footer>
        <div style='Height:5px'></div>
        <center><div class="well well-sm">Copyright © 2020 - <?php echo date('Y') ?> XCSOFT All Rights Reserved.</div></center>
    </footer>