/**
 * Created with JetBrains WebStorm.
 * User: willkan
 * Date: 13-3-31
 * Time: 下午4:49
 * To change this template use File | Settings | File Templates.
 */
define(function(require, exports, module){
    var loading = require('loading'),
        init = require('init'),
        queueAjax = require('queueAjax'),
        showAlert = require('alert'),
        createUrl = 'php/attention_group_new.php',
        renameUrl = 'php/attention_group_rename.php',
        deleteUrl = 'php/attention_group_delete.php',
        $nameError =  $('<span></span>').attr('id','name-error').css('color','red'),
        $move, $group, $moveNotDefine, $groupNotDefine;

    function selectGroup(){
        var $liTarget = $(this),
            groupId = parseInt($liTarget.attr('value'),10);
        if(!$liTarget.hasClass("passive")){
            //update $group
            $(".passive").removeClass("passive");
            $liTarget.addClass("passive");
            if(groupId > -1){
                $move.find('li[value=' + groupId + ']').addClass('passive');
            }
            if(groupId >= 1){
                $('.rename, .delete-group').css('visibility','visible');
            } else{
                $('.rename, .delete-group').css('visibility','hidden');
            }
            //reset selectedCache
            init.reset();
            //loading
            loading.loadingAjax(-1, 'select', groupId, $liTarget.text());
        }
    }

    function existGroup(name){
        var $li = $group.find('li');
        for(var i = 0; i < $li.length; i++){
            if($li.eq(i).text() === name){
                return true;
            }
        }
        return false;
    }

    function createGroup(){
        showAlert(4, {
            confirm: function(){
                var $li = $('<li/>'),
                    name = $('.apprise-input input').val();
                if(name !== '' && name.length <= 8){
                    if(!existGroup(name)){
                        $.ajax({
                            type:'POST',
                            dataType:'JSON',
                            url: createUrl,
                            data:{
                                "groupName":name
                            },
                            success:function(data){
                                $li.text(name);
                                $li.attr('value',data.msg);
                                $li.insertBefore($groupNotDefine);
                                $li.clone().insertBefore($moveNotDefine);
                            },
                            error: function(data){
                                showAlert(5,{
                                    error:data.error
                                });
                            }
                        });
                        Apprise('close');
                    }
                    else{
                        inputNameError('存在同名分组');
                    }
                }
                else{
                    inputNameError('请输入正确的分组名');
                }
            }
        });
    }

    function renameGroup(){
        var timer = setInterval(
            function(){
                var $groupName, origin, $target, groupName, groupId;
                if(($groupName = $('.group-name')).html().indexOf('(') !== -1){
                    clearInterval(timer);
                } else{
                    return ;
                }
                origin = $groupName.html().split('('),
                $target = $('.passive:first'),
                groupId = $target.attr('value');
                showAlert(1, {
                    confirm:function(){
                        var newName = $('.apprise-input input').val();
                        if(newName !== '' && newName.length <= 8){
                            if(!existGroup(newName)){
                                $groupName.html(newName + '(' + origin[1]);
                                queueAjax({
                                    url: renameUrl,
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data:{
                                        "groupName": newName,
                                        "groupId": groupId
                                    },success:function(data){
                                        $('li[value=' + groupId + ']').text(newName);
                                    },
                                    error: function(data){
                                        $groupName.html(origin.join('('));
                                        showAlert(5,{
                                            error:data.error
                                        });
                                    }
                                }, -1, 'renameGroup');
                                Apprise('close');
                            } else{
                                inputNameError('存在同名分组');
                            }
                        } else{
                            inputNameError('请输入正确的分组名');
                        }
                    }
                });
        },20);
        setTimeout(function(){
            clearInterval(timer);
        },10000);
    }

    function inputNameError(text){
        $nameError.text(text).appendTo($('.apprise-content'));
    }

    function deleteGroup(text){
        var $target = $('.passive:first'),
            groupName = $target.text(),
            groupId = $target.attr('value');
        showAlert(0, {
            groupName: groupName,
            confirm: function(){
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        "groupId":groupId
                    },
                    success:function(data){
                        $('li[value=' + groupId + ']').remove();
                        $('li[value="0"]').trigger('click');
                    },
                    error: function(data){
                        showAlert(5,{
                            error:data.error
                        });
                    }
                });
                Apprise('close');
            }
        });

    }

    $(function(){
        $group = $('.group ul');
        $move = $('.move ul');
        $groupNotDefine = $group.find('li[value="0"]');
        $moveNotDefine = $move.find('li[value="0"]');
        //init event
        $group.on('click','li',selectGroup);
        $('#create-group').on('click',createGroup);
        $('.rename').on('click', renameGroup);
        $('.delete-group').on('click', deleteGroup);
    });

});
