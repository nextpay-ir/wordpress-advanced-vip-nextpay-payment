(function() {

    tinymce.create('tinymce.plugins.avVIPMembersShortcode', {

        init : function(ed, url){
            ed.addButton('avVIPMembersShortcode', {
                title : 'اضافه کردن محتویات مخصوص وی آی پی',
                onclick : function() {
                     ed.selection.setContent('[vip-members]' + ed.selection.getContent() + '[/vip-members]');
                },
                image: url + "/vip.png"
            });
        },

        getInfo : function() {
            return {
                longname : 'VIP Shortcode',
                author : 'Vahid Mohamadi',
                authorurl : 'http://vahidd.com/',
                infourl : '',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add('avVIPMembersShortcode', tinymce.plugins.avVIPMembersShortcode);
    
})();
