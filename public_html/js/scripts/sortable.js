$("#sortable_list").sortable({
    update: () => {
        $(".sortable_item").each((index, element) => { 
            $(element).val(index + 1); 
        });
    }
});

$(".sortable").disableSelection();