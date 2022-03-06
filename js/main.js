let jar = [];
let trash = {};

$(".header__burger").on("click", function () {
  $(".header__burger, .header__menu").toggleClass("active");
});

$(".tasteStack").on("click", function () {
  let params = { componentId: $(this).data("id") };
  serverRequest(function (data) {
    jarAdd(data);
    jarRender();
  }, params);
});

$("#clearJar").on("click", function () {
  jarRemoveAll();
});

$("#addToTrash").on("click", function () {
  let params = { jarName: $("#jamName").val().trim(), jarComponents: jar };
  serverRequest(function (data) {
    addTrash(data);
    trashRender();
  }, params);
});

$("#popUpForm").on("submit", function () {
  let params = $(this).serialize();
  console.log(params);
  serverRequest(function (data) {
    alert(data);
    $("#orderPopUp").hide();
    window.location.reload();
  }, params);
  return false;
});

$("#placeOrder").click(function () {
  // orderPopUp.style.display = "flex";
  $("#orderPopUp").attr("style", "display: flex");
});

$("#closeOrderPopUp").click(function () {
  $("#orderPopUp").hide();
});

$("#readyJamList > li").click(function () {
  console.log($(this).data("price"));
  addReadyJamToTrash(this);
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
  // console.log(jar);
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
function jarRemoveAll() {
  jar = [];
  jarRender();
}

function addTrash(jamData) {
  // console.log(jamData);
  trash[jamData.id] = {
    jamId: jamData.id,
    jamName: jamData.name,
    jamQty: 1,
    jamPrice: jamData.price,
  };
  jarRemoveAll();
}

function addReadyJamToTrash(htmlReadyItem) {
  let itemId = $(htmlReadyItem).data("id");
  trash[itemId] = {
    jamId: itemId,
    jamName: $(htmlReadyItem).text(),
    jamQty: 1,
    jamPrice: $(htmlReadyItem).data("price"),
  };
  trashRender();
}

function trashRender() {
  let htmlItemsTrash = "";
  let htmlItemsPopUp = "";
  let sum = 0;

  for (let trashItem in trash) {
    htmlItemsTrash += `<li data-jamId="${trash[trashItem].jamId}">${trash[trashItem].jamName}<input type="number" min="1" max="100" oninput="trashReCounter(this)" value="${trash[trashItem].jamQty}"><span onclick="trashRemove(this)">x</span></li>`;

    htmlItemsPopUp += `<li>${trash[trashItem].jamName}<input type="number" value="${trash[trashItem].jamQty}" disabled></li>`;

    sum += Number(trash[trashItem].jamQty * trash[trashItem].jamPrice);
  }
  $(".sidebar__trash ul").html(htmlItemsTrash);
  $("#orderPopUp ul").html(htmlItemsPopUp);
  $("#fullPrice").html(sum);
  $("input[name='trashItems']").val(JSON.stringify(trash));
  $("#fullOrderPrice").html(sum);
}

function trashRemove(htmlItem) {
  // console.log(trash);
  delete trash[htmlItem.parentElement.attributes["data-jamId"].value];
  trashRender();
}

function trashReCounter(counter) {
  // console.log(counter.value);
  trash[counter.parentElement.attributes["data-jamId"].value].jamQty =
    counter.value;
  trashRender();
}
