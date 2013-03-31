<?php require_once "php/start-session.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>宅不起 | 主办方认证</title>

    <!-- 在head标签内 引入Jquery -->
    <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <!-- 在head标签内 引入Jquery 验证插件 -->
    <script src="js/jquery.validationEngine.js" type="text/javascript"></script>
    <script src="js/jquery.host.js" type="text/javascript"></script>

    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="css/host.css"/>

</head>
<body>
    <?php require_once("top-nav.php"); ?>
    <div id="container">
        <div class="apply-host" id="apply-host">
            <div class="fields">
                <h1>主办方认证</h1>
                <hr/>
                <div id="aside">
                    <h2>什么是主办方？</h2>
                    <p>1. 持续举办活动的组织或团体<br />
                        2. 互动内容属于大学生文化活动的范畴<br />
                        3. 至少成功举办过3次以上活动<br />
                        4. 主办方申请人须为该组织机构的工作人员<br />
                        <br />
                        在此，我们欢迎各种兴趣社团、学生会组织申请！
                    </p>
                </div>
                <div class="relate-inform">您所提交的资料经核实后，即可使用主办方小站的相关功能。</div>
                <form id="apply-form" action="#" class="apply-form" method="post">
                    <table>
                        <tbody>
                            <tr>
                                <td><label for="host-name">主办方名称</label></td>
                                <td>
                                    <input id="host-name" name="host-name" type="text" tabindex="1"
                                    class="validate[required,minSize[2],maxSize[20]]"/>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="host-intro">主办方简介</label></td>
                                <td>
                                    <textarea id="host-intro" name="host-intro" tabindex="2"
                                    class="validate[required]"></textarea>
                              </td>
                            </tr>
                            <tr>
                                <td><label for="host-purpose">主办方用途</label></td>
                                <td>
                                    <textarea id="host-purpose" name="host-purpose" tabindex="3"
                                    class="validate[required]"></textarea>
                              </td>
                            </tr>
                        </tbody>
                    </table>
                <h1>管理员设置</h1>
                <hr/>
                <div class="relate-inform">我们将通过以下信息与小站的管理人员联系，为了便于我们的及时沟通，请确认信息无误。</div>
                    <table>
                        <tbody>
                        <tr>
                            <td><label>管理员账号</label></td>
                            <td id="host-admin">
                                <input type="radio" id="self" name="self"  value="1" checked="true" tabindex="4"/>
                                <label for="self"><?php echo $nickname ?></label>
                                <input type="radio" id="not-self" name="self"  value="0" tabindex="5"/>
                                <label for="not-self">非<?php echo $nickname ?>本人</label>
                            </td>
                        </tr>
                        <tr id="extra" style="display:none">
                            <td><label for="assign-admin">指定管理员账号</label></td>
                            <td>
                                <input id="assign-admin" name="assign-admin" type="text" tabindex="6" class="validate[required]"/>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="admin-name">管理员姓名</label></td>
                            <td>
                                <input id="admin-name" name="admin-name" type="text" tabindex="7"
                                class="validate[required,minSize[2],maxSize[20]]"/>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="admin-tel">联系电话</label></td>
                            <td>
                                <input id="admin-tel" name="admin-tel" type="text" tabindex="8"
                                class="validate[required,custom[phone]]"/>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="admin-email">电子邮箱</label></td>
                            <td>
                                <input id="admin-email" name="admin-email" type="text" tabindex="8"
                                class="validate[required,custom[email]]"/>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="admin-remark">备注</label></td>
                            <td>
                                <textarea id="admin-remark" name="admin-remark" tabindex="10"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" class="submit-btn" name="submit-btn" >确认并提交</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <?php require_once("footer.php"); ?>
</body>
</html>