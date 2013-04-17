/**
 * Created with JetBrains WebStorm.
 * User: willkan
 * Date: 13-3-30
 * Time: 下午6:52
 * To change this template use File | Settings | File Templates.
 */
seajs.config({
    plugin : ['shim'],
    preload: ['jquery'],
    alias: {
        'jquery': 'jquery-1.8.3.min',
        'queueAjax': 'util/queueAjax',
        'loading': 'contacts/contacts.loading',
        'appriseJs': 'apprise-v2.min/apprise-v2.min.js',
        'appriseCss': 'apprise-v2.min/apprise-v2.min.css',
        'group': 'contacts/contacts.group.js',
        'alert': 'contacts/contacts.alert.js',
        'init' : 'contacts/contacts.init.js',
        'person': 'contacts/contacts.person.js'
    }
    //base:js/
});
