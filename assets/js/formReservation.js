/** @type HTMLFormElement */
const form = document.getElementById("form_reservation");
const formError = document.getElementById("form_reservation_error");
const establishment = document.getElementById("reservation_establishment");
const room = document.getElementById("reservation_room");
const startAt = document.getElementById("reservation_startAt");
const endAt = document.getElementById("reservation_endAt");
const submit = document.getElementById("reservation_submit");

establishment?.addEventListener("change", function (e) {
    let data = new FormData(form);

    fetch("/reservation/new", {
        method: "POST",
        body: data,
    })
        .then(function (response) {
            return response.text();
        })
        .then(function (html) {
            let content = document.createElement("html");
            content.innerHTML = html;
            let newSelect = content.querySelector("#reservation_room");
            room.innerHTML = newSelect.innerHTML;
            room.disabled = false;
        });
});

startAt?.addEventListener("change", validatorDate);
room?.addEventListener("change", validatorDate);
endAt?.addEventListener("change", validatorDate);

function validatorDate() {
    if (form.checkValidity()) {
        let data = new FormData(form);
        fetch("/reservation/new/validate", {
            method: "POST",
            body: data,
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.errors) {
                    formError.innerHTML = data.errors[0] ? data.errors[0] : "";
                    submit.disabled = true;
                } else {
                    formError.innerHTML = "";
                    submit.disabled = false;
                }
            });
    } else {
        submit.disabled = false;
    }
}
