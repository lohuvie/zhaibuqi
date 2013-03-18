<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>新建活动</title>

    <!-- 在head标签内 引入Jquery -->
    <script src="js/jquery-1.8.3.min.js" type="text/javascript" ></script>

    <!-- 引入Juqery-ui custom 1.9.2-->
    <script src="js/jquery-ui-1.9.2.custom.min.js" type="text/javascript" ></script>

    <!-- 在head标签内 引入Jquery 验证插件 -->
    <script src="js/jquery.validationEngine.js" type="text/javascript" ></script>
    <script src="js/jquery-ui-timepicker-addon.js" type="text/javascript" ></script> 
    <script src="js/jquery.editable-select.pack.js" type="text/javascript" ></script>
    <script src="js/tag-it.js" type="text/javascript" ></script>
    <script src="js/jquery.new-activity.js" type="text/javascript"></script>

    <link type="text/css" rel="stylesheet" href="css/jquery.editable-select.css" />
    <link rel="stylesheet" type="text/css" href="css/jquery.tagit.css" />
    <link type="text/css" rel="stylesheet" href="css/validationEngine.jquery.css" />
    <link type="text/css" rel="stylesheet" href="css/jquery-ui-1.9.2.custom.css" />
    <link type="text/css" rel="stylesheet" href="js/Jcrop/css/jquery.Jcrop.min.css" />
    <link type="text/css" rel="stylesheet" href="css/jquery-ui-timepicker-addon.css" />    

    <link type="text/css" rel="stylesheet" href="css/new-activity.css"/>
    <link type="text/css" rel="stylesheet" href="css/top-nav.css"/>
    <link type="text/css" rel="stylesheet" href="css/footer.css"/>
</head>
<body>
    <?php require_once("top-nav.php"); ?>
    <div id="container">
        <div id="help">

        </div>
        <div id="new-activity">
            <form enctype="multipart/form-data" action="php/new_activity.php" method="post" id="frmNew">
               <h1>新建活动</h1>
               <table>
                   <tr class="title">
                       <td><label for="title">标题</label></td>
                       <td>
                            <input type="text" id="title" name="title"  tabindex="1"
                            class="validate[required,minSize[2],maxSize[40]] text-input input"/>
                      </td>
                   </tr>
                   <tr class="catogory">
                       <td><label for="category">类型</label></td>
                       <td>
                           <select id="category" name="category" tabindex="2">
                               <option value="club">社团活动</option>
                               <option value="play">出去耍</option>
                               <option value="match">比赛</option>
                               <option value="lecture">讲座</option>
                           </select>
                       </td>
                   </tr>
                   <tr class="time">
                       <td><label>时间</label></td>
                       <td id="time-part">
                           <span>活动日期 </span><br />
                           <input type="text" id="date" class="validate[required,custom[date],past[NOW]] datepicker input " name="date" /><span>（日期格式为:YYYY-MM-DD）</span>
                           <br /><br />
                           <input type="text" name="time-begin" id="time-begin" class="input validate[required] datepicker" value="开始时间"/>
                           <span>&nbsp;至&nbsp;</span>
                           <input type="text" name="time-end" id="time-end" class="input validate[required] datepicker" value="结束时间"/>
                       </td>
                   </tr>
                   <tr class="place">
                       <td><label for="place">地点</label></td>
                       <td>
                          <input type="text" id="place" name="place"  tabindex="4"
                          class="validate[required,minSize[2],maxSize[30]] text-input input"/>
                       </td>
                   </tr>
                   <tr class="introduction">
                       <td><label for="introduction">介绍</label></td>
                       <td>
                        <textarea id="introduction" name="introduction" rows="10" cols="54" tabindex="5"
                        class="validate[required] text-input input"></textarea>
                      </td>
                   </tr>
                   <tr class="cost">
                       <td><label>费用</label></td>
                       <td>
                           <input type="radio" name="cost-class" value="0" tabindex="7" checked="checked"/>
                           <span>免费</span>
                           <input type="radio" name="cost-class" value="1" tabindex="6"/>
                           <span>收费</span>
                       </td>
                   </tr>
                   <tr class="authority">
                       <td><label>权限</label></td>
                       <td>
                           <input type="radio" name="authority-class" value="0" tabindex="8" checked="checked"/>
                           <span>对所有人可见</span>
                           <input type="radio" name="authority-class" value="1" tabindex="9"/>
                           <span>仅好友可见</span>
                       </td>
                   </tr>
                   <tr class="label">
                       <td><label for="tags">标签</label></td>
                       <td><input type="text" id="tags" name="tags" value="电影,社团" tabindex="10"/></td>
                   </tr>
                   <tr class="pic-show">
                       <td><label for="upload-pic">海报</label></td>
                       <td>
                           <div class="loading" id="canvasloader-container"></div>
                           <div id="upload-pic" class="none">请上传照片</div>
                           <div class="upload">
                               <span>选择图片</span>
                               <input type="file" id="poster" name="poster" accept='image/*'/>
                           </div>
                       </td>
                   </tr>
               </table>
                <div class="btn-box">
                    <button type="submit"  id="create" name="create" tabindex="12">新建</button>
                    <button type="button"  id="cancel" name="cancel" tabindex="13">取消</button>
                </div>
            </form>
        </div>
    </div>
    <?php require_once("footer.php"); ?>
    <script src="js/Jcrop/js/jquery.Jcrop.min.js" type="text/javascript"></script>
    <script src="js/jquery.picUpdate.js" type="text/javascript"></script>
    <script src="js/heartcode-canvasloader-min.js" type="text/javascript"></script>
    <script src="js/jquery.new-activity-pic.js" type="text/javascript"></script>
</body>
</html>