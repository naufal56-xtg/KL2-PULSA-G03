document.querySelectorAll("label").forEach((comp) => {
    comp.addEventListener("click", (e) => {
        document.querySelectorAll("label").forEach((e) => {
            e.classList.remove("checked");
        });

        e.target.classList.add("checked");
    });
});
