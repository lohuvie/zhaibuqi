/**
 * Created with JetBrains WebStorm.
 * User: willkan
 * Date: 13-3-30
 * Time: 下午6:51
 * To change this template use File | Settings | File Templates.
 */
define(function(require, exports, module){
    var GROUP_ALL = -1;
    var queueAjax = require('queueAjax'),
        url = 'json/attention_list.php'+location.search,
        $tips,
        $clearLeft = $('<div></div>').css('clear','left'),
        $groupName,
        page = 1,
        group = GROUP_ALL,
        end = false;//表示好友是否加载完毕
    module.exports = {
        currentPage: function(){return page;},
        resetPage: resetPage,
        loadingAjax: loadingAjax
    };
    /* reset page to 1 */
    function resetPage(selectGroup){
        page = 0;
        end = false;
        group = typeof selectGroup === 'number' ? selectGroup : GROUP_ALL;
    }
    /*
    * @param setting {num} 1:firstAjax,-1:lastAjax;other:Ajax
    * @param namespace {string} ajaxQueue name
    * @param update {boolean}
    * */
    function loadingAjax(setting, namespace, update, groupName){
        if(typeof update === 'number'){
            $groupName.text(groupName);
            $('.user-display').remove();
            resetPage(update);
        }
        if(!end){
            $tips.text('正在加载...').show();
            queueAjax({
                type:"GET",
                dataType:"JSON",
                url: url,
                data:{
                    "page":page,
                    "group":group
                },
                success:appendPerson,
                error:function(){
                    $tips.text('加载失败请点击重试').show();
                }
            }, setting, namespace);
        } else{
            $tips.text('所有好友加载完毕').css('cursor','default').show();
        }
    }
    /*
    * @param data{portrait,href,name,institude,group}
    * */
    function appendPerson(data){
        if(data.groupNum){
            $groupName.append('(<span>' + data.groupNum + '</span>个)');
        }
        page++;
        data.person && $.each(data.person, function(){
            var portrait = this.portrait,
                href = this.href,
                name = this.name,
                academy = this.academy,
                group = "组别:"+this.group,
                af_id = this.af_id,
                ag_id = this.ag_id,
                $img = $("<img />").attr({
                    "src":portrait,
                    "alt":name
                }),
                $name = $('<p></p>').append($('<a></a>').addClass('name').attr('href',href).text(name)),
                $academy = $('<p></p>').text(academy),
                $portrait = $("<a></a>").addClass("pic").attr("href",href),
                $userInfo = $("<div></div>").addClass("user-info"),
                $group = $("<p></p>").attr('value',ag_id).addClass("extra").text(group),
                $userDisplay = $("<div></div>").attr('value',af_id).addClass("user-display");
            $userInfo.append($name, $academy);
            $userDisplay.append($portrait.append($img),
                $userInfo,$clearLeft.clone(),$group).insertBefore($tips);
            $userDisplay.fadeIn(100);
        });
        end = (data.end === 'end');
        if(end){
            $tips.text('所有好友加载完毕').css('cursor','default').show();
        }
    }

    /*初始化页面*/
    $(function(){
        $groupName = $('.group-name');
        $tips = $('.clear');
    });
});
