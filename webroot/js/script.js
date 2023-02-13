// jQuery & Velocity.js

function slideUpIn() {
  $("#login").velocity("transition.slideUpIn", 1700)
  $("#text").velocity("transition.slideUpIn", 1700)
};

function slideLeftIn() {
  $(".row").delay(700).velocity("transition.slideLeftIn", {stagger: 700})    
}

function shake() {
  $(".password-row").velocity("callout.shake");
}

slideUpIn();
slideLeftIn();

//$("button").on("click", function () {
//  shake();
//});



/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
} 