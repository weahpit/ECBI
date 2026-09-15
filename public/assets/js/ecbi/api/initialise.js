function saveAdmin(formData){
    fetch("/saveInfosSuperAdmin", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}
function savePersonnalisation(formData){
    fetch("/saveInfosCustomize", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}

function saveInfosSociete(formData){
    fetch("/saveInfosSociete", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}

