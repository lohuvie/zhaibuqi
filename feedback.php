 <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>宅不起 | 意见反馈</title>

    <!-- 在head标签内 引入Jquery -->
    <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <!-- 在head标签内 引入Jquery 验证插件 -->
    <script src="js/jquery.validationEngine-cn.js" type="text/javascript"></script>
    <script src="js/jquery.validationEngine.js" type="text/javascript"></script>

    <script type="text/javascript">  
        $(document).ready(function() {
            //验证留言表单
            $("#fbk-form").validationEngine({ 
                promptPosition: "bottomRight",   //topLeft, topRight, bottomLeft, centerRight, bottomRight
                inlineValidation: false
            })
        });
    </script>
    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />

    <link type="text/css" rel="stylesheet" href="css/top-nav.css"/>
    <link type="text/css" rel="stylesheet" href="css/footer.css"/>
    <link type="text/css" rel="stylesheet" href="css/feedback.css"/>
</head>
<body>
    <?php require_once("top-nav.php"); ?>
    <div id="container">
        <div id="fbk-reply-box" class="fbk-reply-box">
            <h2>我建议 ...</h2>
            <form id="fbk-form" class="fbk-form" action="">
                <p><label for="comment-area">评论内容 (*必填)</label></p>
                <div>
                    <textarea class="validate[required,length[6,300]] text-input" id="comment-area" name="comment-area" cols="30" rows="10" ></textarea>
                </div>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <label for="author">你的大名 (*必填)</label>
                                <br/>
                                <input id="author" type="text" name="author" value=""
                                class="validate[required] text-input"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="email">你的邮箱 (*必填)</label>
                                <br/>
                                <input id="email" type="text" name="email" value=""
                                class="validate[required,custom[email]] text-input" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="bug-url">问题链接 (可选填)</label>
                                <br/>
                                <input id="bug-url" type="text" name="bug-url" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="agree-cb">
                                    <input type="checkbox" name="is_agree"/> 有新评论时，通过邮箱通知我吧
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p><input id="submit-btn" type="submit" name="submit" value="提交反馈"/></p>
            </form>
            <div class="fbk-contact">
                <h3>联系我们：</h3>
                <p>
                    电话：
                    <br />
                    邮箱：<a href="#"></a>
                </p>
            </div>
        </div>
        <div id="fbk-content" class="fbk-content">
            <div class="fbk-header">
                <h2>用户反馈</h2>
            </div>
            <div class="fbk-body">
                <ul>
                    <li class="user-post">
                        <div class="user-subject">
                            <a href="#">
                                <img class="user-photo" alt="用户" src="#"/>
                            </a>
                            <div class="reply-detail">
                                <h3>
                                    <a href="#" class="user-name">姓名1</a>
                                </h3>
                                <p class="content">评论内容.</p>
                            </div>
                            <div class="reply-time">
                                <a>回复</a>
                                10月4日 22:47
                            </div>
                        </div>
                    </li>
                    <li class="user-post">
                        <div class="user-subject">
                            <a href="#">
                                <img class="user-photo" alt="用户" src="#"/>
                            </a>
                            <div class="reply-detail">
                                <h3>
                                    <a href="#" class="user-name">姓名1</a>
                                </h3>
                                <p class="content">评论内容.</p>
                            </div>
                            <div class="reply-time">
                                <a>回复</a>
                                10月4日 22:47
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="page-nav">
                <a class="active" href="#">1</a>
                <a href="#">2</a>
                <a href="#">下一页</a>
            </div>
        </div>
    </div>
    <?php require_once("footer.php"); ?>
</body>
 </html>

