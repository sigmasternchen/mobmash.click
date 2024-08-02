var spinnerActive = false;

const triggerAnimation = () => {
    setTimeout(() => {
        document.getElementsByClassName("middle")[0].classList.add("loading");
    }, 1);
}

const endAnimation = () => {
    document.getElementsByClassName("middle")[0].classList.remove("loading");
}

window.startSpinner = () => {
    if (spinnerActive) {
        return
    }
    spinnerActive = true;
    document.getElementsByClassName("middle")[0].classList.add("loading");
}

window.stopSpinner = () => {
    spinnerActive = false;
}

window.addEventListener("load", () => {
    document.querySelector(".middle .spinner img")
        .addEventListener("animationend", () => {
            endAnimation();
            if (spinnerActive) {
                triggerAnimation();
            }
        });
});