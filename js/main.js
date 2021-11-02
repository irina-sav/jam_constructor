let jar = [];

function  componentCounter(){
    let componentCount = 0;
    for(let componentItem in jar){
        componentCount++;
    }
    return componentCount;
}
function jarAdd(component){
    if(componentCounter() < 2 && jar[component.id] === undefined) {
        jar[component.id] = component;
    }
    
}
function jarRender(){
    console.log(jar);
    let html = "";
    let jamName = "";
    for(let component in jar){
        html += 
        `<div class="taste jarTaste" style="background-image: url(${jar[component].picture})" onclick="jarRemove(${jar[component].id})">${jar[component].name}</div>`;
        jamName += `${jar[component].name} `;
    }
    $(".jamjar").html(html);
    $("#jamName").val(jamName.trim());
}



$(".tasteStack").on("click", function(){    
    let params = {componentId: $(this).data("id")};
    serverRequest(function(data){
       jarAdd(data);
       jarRender();
          
    }, params);

});

function serverRequest(callBackFunction, params = null){
    const url = "api_controller.php";
    $.post(url, params, function(data){
        // console.log(data);
        let dataObject = JSON.parse(data);
        callBackFunction(dataObject, params = null);
    });
}

function jarRemove(componentId){
    delete jar[componentId];
     jarRender();
}

 
