// Nestable demo
// ----------------------------------- 


(function(window, document, $, undefined){

  $(function(){

    var updateOutput = function(e)
    {

        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
        } else {
            output.val('JSON is not supported');
        }
    };

    // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1
    })
    .on('change', updateOutput);


    // output initial serialised data
    updateOutput($('#nestable').data('output', $('#nestable-output')));

    $('.js-nestable-action').on('click', function(e)
    {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });
    
    $('.dd-handle').on('click',function(){

        var selected_theme_id = $(this).data('theme-id');
        $('.dd-handle').css("background-color","#fff");
        $(this).css("background-color","#a5e79b");
        $("#theme-descr").load("/theme/description?id="+ selected_theme_id);
    
    });
  });

})(window, document, window.jQuery);