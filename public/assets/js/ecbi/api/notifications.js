

function getUsersByNotification(id_notification, value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_notifications = ''
    let contenu_body = ''
    ctrl_name.innerHTML = ''
    $.ajax({
        url : '/getUsersByNotification/' + id_notification,
        type : 'POST',
        success: function(response){
            let listeNotifications = JSON.parse(response);
            if (listeNotifications.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                  contenu_notifications = `
                <div class="container mx-auto">
                    <label for="users" class="fw-bold">Utilisateurs</label>
                    <select class="ms-2" name="select_users" id="select_users" style="width: 300px;background: #ffffff;border: 1px solid #555555;"></select>
                    <button type="button" class="btn btn-primary ms-2 p-0 ps-2 pe-2" title="Ajouter un utilisateur à la liste de diffusion" id="btn_proforma_add_user"><i class="fa fa-plus"></i></button>
                    <table class="table table-hover table-striped mt-1 mt-2 " id="table_notifications">
                        <thead class="p-0" style="padding: 0;">
                            <tr class="w-100 p-1" style="width: 100%;padding: 0;">
                                <th class="fs-7 fw-light" style="background: linear-gradient(#aaaaa9,#797977);color:white;">Utilisateurs sélectionnés</th>
                            </tr>
                        </thead>
                        <tbody id="body_notifications">
                        </tbody>
                    </table>
                    </div>
                  `
                    ctrl_name.innerHTML = contenu_notifications;
                            for (var i=0; i < listeNotifications.data.length ; i++){
                                contenu_body +='<tr><i class="fa fa-user me-2"></i><a class="ps-4 text-dark mt-1" href="#" id="'+ listeNotifications.data[i].id + '" >' + listeNotifications.data[i].nom_prenoms  + ' (<span class="fw-bold" style="background: transparent !important;">' + listeNotifications.data[i].email  + '</span>)</a></tr>'
                            }
                    document.getElementById("body_notifications").innerHTML = contenu_body


                } else { // le contrôle est un dropdown
                    contenu_notifications +='<option value="0">Notifications</option>'
                    for (var i=0; i < listeNotifications.data.length ; i++){
                        contenu_notifications +='<option value="'+ listeNotifications.data[i].id + '">' + listeNotifications.data[i].nom_prenoms  + '</option>'
                    }
                    ctrl_name.innerHTML = contenu_notifications;
                }


                getUsersHasNotNotification(id_notification, 2, document.querySelector("#select_users"))
            } else {
                render_toast_value(listeNotifications.msg, listeNotifications.code)
            }
        }
    })
}


function SaveUserInListAlertesDoc(id_user, id_modele_doc){
    $.ajax({
        url : 'SaveUserInListAlertesDoc/' + id_user + '/' + id_modele_doc,
        success : function (response){
            render_toast_value(response.msg, response.code)
            if (response.code === 'success'){
                getUsersByNotification(type_doc.value, 1, document.querySelector("#div_users"))
            }
        }
    })
}
function getUsersHasNotNotification(id_notification, value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_notifications = ''
    $.ajax({
        url : '/getUsersHasNotNotification/' + id_notification,
        type : 'POST',
        success: function(response){
            let listeNotifications = JSON.parse(response);
            if (listeNotifications.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_notifications = `
                  <label for="users" class="fw-bold">Utilisateurs</label>
                    <select class="ms-2" name="users" id="users">
                    </select><a href="#" class="btn btn-primary ms-2 p-0 ps-2 pe-2"><i class="fa fa-plus"></i></a>
                    <table class="mt-2 table table-hover table-striped mt-1" id="table_notifications">
                        <thead>
                        <tr class="w-100" style="width: 100%;">
                            <th class="fs-7 fw-light" style="background: linear-gradient(#aaaaa9,#797977);color:white;">Utilisateurs sélectionnés</th>
                        </tr>
                        </thead>
                        <tbody id="body_notifications">
                  `
                    for (var i=0; i < listeNotifications.data.length ; i++){
                        contenu_notifications +='<tr><a href="#" id="'+ listeNotifications.data[i].id + '" >' + listeNotifications.data[i].nom_prenoms  + '</a></tr>'
                    }

                    contenu_notifications +='</tbody>'
                    contenu_notifications +='</table>'

                } else { // le contrôle est un dropdown
                    contenu_notifications +='<option value="0">Sélectionnez un utilisateur</option>'
                    for (var i=0; i < listeNotifications.data.length ; i++){
                        contenu_notifications +='<option value="'+ listeNotifications.data[i].id + '">' + listeNotifications.data[i].nom_prenoms  + '</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_notifications;
            } else {
                render_toast_value(listeNotifications.msg, listeNotifications.code)
            }
        }
    })
}

function getDocumentsNotifications(value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_docs = ''
    $.ajax({
        url : '/getDocumentsNotifications',
        type : 'POST',
        success: function(response){
            let listeDocs = JSON.parse(response);
            if (listeDocs.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_docs = `
                    <table class="mt-2 table table-hover table-striped mt-1" id="table_notifications">
                        <thead>
                        <tr class="w-100" style="width: 100%;">
                            <th class="fs-7 fw-light" style="background: linear-gradient(#aaaaa9,#797977);color:white;">Documents</th>
                        </tr>
                        </thead>
                        <tbody id="body_documents">
                          `
                            for (var i=0; i < listeDocs.data.length ; i++){
                                contenu_docs +='<tr><a href="#" id="'+ listeDocs.data[i].id + '" >' + listeDocs.data[i].doc + '</a></tr>'
                            }
                    contenu_docs +='</tbody>'
                    contenu_docs +='</table>'

                } else { // le contrôle est un dropdown
                    contenu_docs +='<option value="0">Sélectionnez un document</option>'
                    for (var i=0; i < listeDocs.data.length ; i++){
                        contenu_docs +='<option value="'+ listeDocs.data[i].id + '">' + listeDocs.data[i].doc + '</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_docs;
            } else {
                render_toast_value(listeDocs.msg, listeDocs.code)
            }
        }
    })
}

    function enregistre_notifications(id_notification, value){
        $.ajax({
            url : 'saveOption/' + id_notification + '/' + value,
            type: 'POST',
            success: function (response){
                render_toast_value(response.msg, response.code)
                if (response.code === 'success') {getOptions(1, document.querySelector(".div_notifications"))}
            }
        })
    }
