$(".tasteStack").on("click", function(){
    const url = "api_controller.php";
    let params = {componentId: $(this).data("id")};
    $.post(url, params, function(data){
        console.log(data);
        let dataObject = JSON.parse(data);
        $(".jamjar").html(`<div class="taste" style="background-image: url(${dataObject.picture})">${dataObject.name}</div>`);
    });
});