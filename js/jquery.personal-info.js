/* 
 * User: lemonz-
 * Date: 13-3-12
 */

$(document).ready(function() {
    //验证基本信息
    $("#personal-info").validationEngine({
        promptPosition: "centerRight"   //topLeft, topRight, bottomLeft, centerRight, bottomRight
    });
    var availableTags = [
        "经济学院",
        "水利水电学院",
        "法学院",
        "化学工程学院",
        "文学与新闻学院",
        "轻纺与食品学院",
        "外国语学院",
        "高分子科学与工程学院",
        "艺术学院",
        "华西基础医学与法医学院",
        "历史文化学院(旅游学院)",
        "华西临床医学院",
        "数学学院",
        "华西第二医院",
        "物理科学与技术学院",
        "华西口腔医学院",
        "化学学院",
        "华西公共卫生学院",
        "生命科学学院",
        "华西药学院",
        "电子信息学院",
        "公共管理学院",
        "材料科学与工程学院",
        "商学院",
        "制造科学与工程学院",
        "政治学院",
        "电气信息学院",
        "体育学院",
        "计算机学院",
        "软件学院",
        "建筑与环境学院",
        "吴玉章学院"
    ];
    $( "#institude" ).autocomplete({
        source: availableTags
    });
});