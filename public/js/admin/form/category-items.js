
function addItem(e) {
    let index = 0

    let html = '<div class="category_item form-group">' +
        '<div class="input-group">' +
        '<input type="text" name="category[' + index + '][category_conditions][]" class="form-control">' +
        '<a class="input-group-addon remove-category-item"><i class="fa fa-window-close fa-fw"></i>' +
        '</div>' +
        '</div>'

    $(e).parent('.category-items').children('.category-items-list').append(html);
}

// document.addEventListener('DOMContentLoaded', function () {
//     // do something here ...
//   }, false);



$(function () {

    // $("#ca-btn").bind("click", function () {
    //     var div = $("<tr />");
    //     div.html(GetDynamicTextBox(""));
    //     $("#TextBoxContainer").append(div);
    // });
    $("body").on("click", ".remove-category-item", function () {
        $(this).closest(".category_item").remove();
    });
});