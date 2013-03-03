$(document).ready(function() {
    //下拉可编辑
    $('.editable-select').editableSelect(
      {
        bg_iframe: true,
        onSelect: function(list_item) {
          $('#results').html('List item text: '+ list_item.text() +'<br > \
          Input value: '+ this.text.val());
        }
      }
    );
});