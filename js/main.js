let jar = [];
let trash = {};

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
  trash[jamId] = {jamId: jamId, jamName: jamName, jamQty: 1 };
}

function trashRender() {
  let htmlItems = "";
  let sum = "";
  console.log(trash);
  for (let trashItem in trash) {
    htmlItems += `<li data-jamId="${trash[trashItem].jamId}">${trash[trashItem].jamName}<input type="number" min="1" max="100" oninput="trashReCounter(this)" value="${trash[trashItem].jamQty}"><span onclick="trashRemove(this)">x</span></li>`;
    sum += (trash[trashItem].jamQty * trash[trashItem].jamPrice);
  }
  $(".sidebar__trash ul").html(htmlItems);
  $("#fullPrice").html(sum);
}

function trashRemove(htmlItem) {
  console.log(trash);
  delete trash[htmlItem.parentElement.attributes['data-jamId'].value];
  trashRender();
}

function trashReCounter(counter) {
  console.log(counter.value);
  trash[counter.parentElement.attributes['data-jamId'].value].jamQty = counter.value;
}