$(function(){
    var sampleTags = ['望江', '江安', '成都', '篮球赛', '足球赛', '电影','2013','讲座','比赛','竞赛','部门','NBA','宅不起','Zhaibuqi','体育部','文艺部'];

    //this is an INPUT element, rather than a UL as in the other 
    $('#tags').tagit({
        availableTags: sampleTags
        //, allowSpaces: true
        //, removeConfirmation: true
    });
});