window.addEventListener("load", (event) => {

    let div = document.getElementById('output2');
    console.log(div.children);
    div.children[5].classList.remove("mt-3");
    div.children[6].classList.remove("mt-3");

  });