/**
 * Created with JetBrains WebStorm.
 * User: willkan
 * Date: 13-4-6
 * Time: 下午1:59
 * To change this template use File | Settings | File Templates.
 */
define(function(require, exports, module){
    var init = require('init'),
        showAlert = require('alert'),
        moveUrl = 'php/attention_person_move.php',
        deleteUrl = 'php/attention_person_delete.php';
    function move(){
        var cache = init.getCache(),
            targetGroup = $(this);
        if(!targetGroup.hasClass('passive') && cache.length !== 0){
            showAlert(3, {
                confirm : function(){
                    $.ajax({
                        url: moveUrl,
                        type: 'POST',
                        dataType:'JSON',
                        data: {
                            person: JSON.stringify(cache),
                            groupId: targetGroup.attr('value')
                        },
                        success: function(){
                            $('.active').remove();
                            init.reset();
                        },
                        error: function(data){
                            showAlert(5,data.error);
                        }
                    });
                    Apprise('close');
                },
                personName: cache.length === 1 ? $('.active .name').text() : null,
                groupName: targetGroup.text()
            });
        }
    }

    function deletePerson() {
        var cache = init.getCache();
        if(cache.length !== 0){
            showAlert(2, {
                confirm : function(){
                    $.ajax({
                        url: deleteUrl,
                        type: 'POST',
                        dataType:'JSON',
                        data: {
                            person: JSON.stringify(cache)
                        },
                        success: function(){
                            $('.active').remove();
                            init.reset();
                        },
                        error: function(data){
                            showAlert(5,data.error);
                        }
                    });
                    Apprise('close');
                },
                personName: cache.length === 1 ? $('.active .name').text() : null
            });

        }
    }

    $(function(){
        $('.move ul').on('click', 'li', move);
        $('.remove').on('click', deletePerson);
    });


});
