let jar = [];
let trash = [];

$(".tasteStack").on("click", function () {
  let params = { componentId: $(this).data("id") };
  serverRequest(function (data) {
    jarAdd(data);
    jarRender();
  }, params);
});

$("#addToTrash").on("click", function () {
  let params = { jarName: $("#jamName").val().trim(), jarComponents: jar };
  serverRequest(function (data) {
    addTrash(data);
    trashRender();
    console.log(data);
  }, params);
});

function componentCounter() {
  let componentCount = 0;
  for (let componentItem in jar) {
    componentCount++;
  }
  return componentCount;
}
function jarAdd(component) {
  if (componentCounter() < 2 && jar[component.id] === undefined) {
    jar[component.id] = component;
  }
}
function jarRender() {
  console.log(jar);
  let html = "";
  let jamName = "";
  let componentsList = "";
  for (let component in jar) {
    html += `<div class="taste jarTaste" style="background-image: url(${jar[component].picture})" onclick="jarRemove(${jar[component].id})">${jar[component].name}</div>`;
    jamName += `${jar[component].name}, `;
    componentsList += `${jar[component].id},`;
  }
  $(".jamjar").html(html);
  $("#jamName").val(jamName.trim().slice(0, -1));
  $("#jamName").data("componentsList", componentsList.trim().slice(0, -1));
}

function serverRequest(callBackFunction, params = null) {
  const url = "api_controller.php";
  $.post(url, params, function (data) {
    console.log(data);
    let dataObject = JSON.parse(data);
    callBackFunction(dataObject, (params = null));
  });
}

function jarRemove(componentId) {
  delete jar[componentId];
  jarRender();
}
function addTrash(jamId) {
  let jamName = $("#jamName").val();
  trash.push({ jamId: jamId, jamName: jamName, jamQty: 1 });
}
function trashRender() {
  let htmlItem = "";
  for (let trashItem in trash) {
    htmlItem += `<li data-jamId="${trash[trashItem].jamId}">${trash[trashItem].jamName}<input type="number" min="1" max="100" value="${trash[trashItem].jamQty}"><span onclick="trashRemove(this)">x</span></li>`;
  }
  $(".sidebar__trash ul").html(htmlItem);
}
function trashRemove(htmlItem) {
  console.log(htmlItem.parent.getAttribute("data-jamId"));
  // delete trash[htmlItem];
  trashRender();
}
