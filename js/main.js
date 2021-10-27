let jar = [];
let componentCount = 0;
function jarAdd(component){
    if(componentCount < 1) {
       for(let componentItem in jar){
        componentCount++;
    }
    console.log(componentCount);
        jar[component.id] = component;
        return true;
    }
    
    return false;
}
function jarRender(){
    console.log(jar);
    let html = "";
    for(let component in jar){
        html += `<div class="taste" style="background-image: url(${jar[component].picture})">${jar[component].name}</div>`;
    }
    $(".jamjar").html(html);
}

$(".tasteStack").on("click", function(){    
    let params = {componentId: $(this).data("id")};
    serverRequest(function(data){
       if(jarAdd(data)) {
            jarRender();
       }
       
    }, params);
    // $.post(url, params, function(data){
    //     console.log(data);
    //     let dataObject = JSON.parse(data);
    //     $(".jamjar").html(`<div class="taste" style="background-image: url(${dataObject.picture})">${dataObject.name}</div>`);
    // });
});

function serverRequest(callBackFunction, params = null){
    const url = "api_controller.php";
    $.post(url, params, function(data){
        // console.log(data);
        let dataObject = JSON.parse(data);
        callBackFunction(dataObject, params = null);
    });
}
