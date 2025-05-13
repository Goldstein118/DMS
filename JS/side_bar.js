
function w3_open() {
document.getElementById("main").style.marginLeft = "25%";
document.getElementById("mySidebar").style.width = "25%";
document.getElementById("mySidebar").style.display = "block";
document.getElementById("openNav").style.display = 'none';

}
function w3_close() {
document.getElementById("main").style.marginLeft = "0%";
document.getElementById("mySidebar").style.display = "none";
document.getElementById("openNav").style.display = "inline-block";

}

document.addEventListener('DOMContentLoaded', function () {
document.getElementById('openNav').addEventListener('click', w3_open);
document.getElementById('closeNav').addEventListener('click', w3_close);
});
