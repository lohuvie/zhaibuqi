/**
 * Created with JetBrains WebStorm.
 * User: willkan
 * Date: 13-4-2
 * Time: 下午7:32
 * To change this template use File | Settings | File Templates.
 */
define(function(require, exports, module){
    require('appriseJs');
    module.exports = showAlert;
    var DELETE_GROUP = 0,
        RENAME_GROUP = 1,
        DELETE_PERSON = 2,
        MOVE_PERSON = 3,
        CREATE_GROUP = 4,
        ERROR = 5,
        text = '',
        options = {
            animation: 300,
            buttons: {
                confirm: {
                    id: 'confirm', // Element ID
                    text: '确定' // Button text
                },
                cancelable: {
                    action: function(){ Apprise('close'); },
                    id: 'cancel', // Element ID
                    text: '取消' // Button text
                }
            },
            input: false, // input dialog
            override: true // Override browser navigation while Apprise is visible
        };
    /*
    * @param mode {number}
    * @param opt {confirm:{function},
    *     groupName:{string},
    *     personName:{string}}
    * */
    function showAlert(mode, opt){
        switch (mode){
            case DELETE_GROUP:
                text = '确定要删除 <b>' + opt.groupName +'</b> 分组吗？<br/>此分组下的人将会被移动到未分组中，并不会取消关注';
                break;
            case RENAME_GROUP:
                text = '请输入新的分组名: (最多8个字)';
                break;
            case DELETE_PERSON:
                text = '确定要取消对' +
                    (typeof opt.personName === 'string' ? '"' + opt.personName + '"' : '这些人') +
                    '的关注吗？';
                break;
            case MOVE_PERSON:
                text = '确定要将' +
                    (typeof opt.personName === 'string' ? '"' + opt.personName + '"' : '这些人') +
                        '移动到<b>' + opt.groupName + '</b>中吗?';
                break;
            case CREATE_GROUP:
                text = '请输入分组名:(最多8个字)';
                break;
            case ERROR:
                Apprise(opt.error,{
                    confirm: false
                });
                return;
                break;
            default:
                break;
        }
        options.buttons.confirm.text = mode === RENAME_GROUP ? '保存' : '确定';
        options.input = [RENAME_GROUP, CREATE_GROUP].indexOf(mode) !== -1 ? true : false;
        options.buttons.confirm.action = opt.confirm;
        Apprise(text, options);
    }
});
