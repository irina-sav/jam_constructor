document.getElementById("button").onclick = function(){
    // event.preventDefault();
    

    let color = document.getElementsByName("color")[0].value;
    let text = document.getElementsByName("text")[0].value;

    const request = new XMLHttpRequest;
    const url = "api.php";
    const params = `color=${color}&text=${text}`;

    request.open("POST", url, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = () => {
        if(request.readyState === 4 && request.status === 200){
            console.log(request.response);
            document.getElementById("result").innerHTML += request.response + "<br><br>";
            
        }
    };
    request.send(params);
    return false;
};