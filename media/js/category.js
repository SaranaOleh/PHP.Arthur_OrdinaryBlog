"use strict";
var test2 = document.querySelector("main>h1");
var getCat = document.querySelector(".icon-"+test2.dataset.selector);
var test = getComputedStyle(getCat);
var post = document.querySelectorAll(".post");
test2.style.backgroundColor = test.color;
post.forEach(function(elem){
    elem.style.borderBottom = "5px solid "+test.color;
});
console.log(test);