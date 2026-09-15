function getSocieteInfos( ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_options = ''
    $.ajax({
        url : '/getSocieteInfos',
        type : 'POST',
        success: function(response){
            if (response.code === 'success'){
                ctrl_name.innerHTML = `
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:200px;">Sigle<span class="ms-2 text-danger fw-bold">*</span></span>
                            <input type="text" class="form-control fw-bold text-danger" id="sigle" required aria-label="Code du client" aria-describedby="rs_client" style="text-transform: uppercase;" value="${response.sigle}">
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:200px;">Raison Sociale<span class="ms-2 text-danger fw-bold">*</span></span>
                            <input type="text" class="form-control fw-bold text-secondary ps-4" id="rs_societe" required aria-label="Code du client" aria-describedby="rs_client" style="text-transform: uppercase;" value="${response.rs}">
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:200px;">Email<span class="ms-2 text-danger fw-bold">*</span></span>
                            <input type="text" class="form-control fw-bold text-secondary ps-4" id="rs_email" required aria-label="Code du client" aria-describedby="rs_client" value="${response.email}">
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:200px;">Adresse<span class="ms-2 text-danger fw-bold">*</span></span>
                            <input type="text" class="form-control fw-bold text-secondary ps-4" id="rs_adresse" required aria-label="Code du client" aria-describedby="rs_adresse"  value="${response.adresse}">
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:200px;">Mobile<span class="ms-2 text-danger fw-bold">*</span></span>
                            <input type="text" class="form-control fw-bold text-secondary ps-4" id="rs_mobile" required aria-label="Code du client" aria-describedby="rs_mobile"  value="${response.mobile}">
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:200px;">Téléphone<span class="ms-2 text-danger fw-bold">*</span></span>
                            <input type="text" class="form-control fw-bold text-secondary ps-4" id="rs_tel" required aria-label="Code du client" aria-describedby="rs_tel" value="${response.tel}">
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:200px;">Compte Contribuable<span class="ms-2 text-danger fw-bold">*</span></span>
                            <input type="text" class="form-control fw-bold text-secondary ps-4" id="rs_cc" required aria-label="Code du client" aria-describedby="rs_cc" style="text-transform: uppercase;" value="${response.cc}">
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:200px;">Registre de commerce<span class="ms-2 text-danger fw-bold">*</span></span>
                            <input type="text" class="form-control fw-bold text-secondary ps-4" id="rs_rccim" required aria-label="Code du client" aria-describedby="rs_rccim" style="text-transform: uppercase;" value="${response.rccim}">
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:200px;">Centre des impôts<span class="ms-2 text-danger fw-bold">*</span></span>
                            <input type="text" class="form-control fw-bold text-secondary ps-4" id="rs_centre_impots" required aria-label="Code du client" aria-describedby="rs_centre_impots" style="text-transform: uppercase;" value="${response.centre_impots}">
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:200px;">Type Imposition<span class="ms-2 text-danger fw-bold">*</span></span>
                             <select  class="W-100" id="rs_type_impots" aria-label="" required aria-describedby="type_impots" style="width: 75%;">
                            </select>
                       </div>
                       <div class="text-center bg-light p-3 rounded shadow-sm">
                          <span class="fw-bold d-block mb-2">Insérer un logo</span>

                          <!-- Bouton personnalisé -->
                          <label for="logoUpload" class="btn btn-primary  mb-2">
                            <img class="p-1" src="assets/icons/upload_image.png" alt="">
                          </label>
                          <input type="file" id="logoUpload" accept="image/*" hidden>

                          <!-- Contraintes -->
                          <small class="text-danger d-block mb-2">
                            Image de moins de 200Ko | taille : 200px x 200px
                          </small>

                          <!-- Aperçu -->
                          <img id="logoPreview"
                               style="width:200px;height:200px;border:2px dashed #000;object-fit:cover;"
                               src="" alt="Aperçu du logo">
                        </div>
                       <div class="text-center p-2">
                            <button type="button" class="btn btn-primary p-2" id="btn-enregistrer-societe">Enregistrer</button>
                        </div>
                `
                uploadImage(document.getElementById('logoUpload'), document.getElementById('logoPreview'));
            }
        }
    })
}
function getCustomInfos( ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_options = ''
    $.ajax({
        url : '/getCustomInfos',
        type : 'POST',
        success: function(response){
                ctrl_name.innerHTML = `
                        <form id="formCustomize">
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:250px;border: 0;">Police <span class="ms-2 text-danger fw-bold">*</span></span>
                             <select  class="mb-1 mt-1" id="police" aria-label="" required aria-describedby="police" style="width: 25%;padding: 0;">
                                    <option value="Arial, serif">Arial</option>
                                    <option value="Cambria, serif">Cambria</option>
                                    <option value="'Century Gothic', sans-serif">Century Gothic</option>
                                    <option value="'Comic Sans MS',serif">Comic Sans MS</option>
                                    <option value="'Courier New',serif">Courier New</option>
                                    <option value="Roboto, serif">Roboto</option>
                                    <option value="'Segoe UI',serif">Segoe UI</option>
                                    <option value="Tahoma, serif">Tahoma</option>
                                    <option value="'Times New Roman', serif">Times New Roman</option>
                                    <option value="Verdana, serif">Verdana</option>
                            </select>
                       </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2" style="width: 95%;border-bottom: 1px solid #dea700;font-family: 'Segoe UI',serif">
                            <span class="input-group-text fw-bold" style="width:250px;border: 0;">Icône de l'application<span class="ms-2 text-danger fw-bold">*</span></span>
                            <select  class="mb-3 mt-3" id="type_icone" aria-label="" required aria-describedby="police" style="width: 25%;padding: 0;">
                                    <option value="Image">Image</option>
                                    <option value="Font_Awesome">Font awesome</option>
                            </select>
                            <div class="upload_icone ms-4" id="upload_icone">
                                    <!-- Bouton personnalisé -->
                                  <label for="logoUploadIcone" class="btn mb-2 mt-2 p-1 btn-light" style="min-width:20px;border-radius: 0;font-size: 13px;">
                                  <!-- <img class="p-1" src="assets/icons/upload_image.png" alt="">-->
                                  <img src="assets/icons/telecharger.png" alt="" style="font-size:16px;" class="me-2">Charger
                                  </label>
                                  <input type="file" id="logoUploadIcone" accept="image/*" hidden>
                                  <!-- Aperçu -->
                                  <img id="logoPreviewIcone"
                                       style="height: 20px;object-fit:cover;border: 0:" src="" alt="">
                            </div>
                            <input type="text" class="form-control fw-bold text-secondary ms-2 mb-3 mt-3" id="icone_application" required aria-label="Code du client" aria-describedby="icone_application"  value="" style="max-width: 200px;display: none;font-size:13px;font-weight: lighter; " placeholder="Copier le code Font-Awsome ici Ex: <i class='fa fa-solid fa-star'></i>">
                            </div>
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:250px;border: 0;">Favicon<span class="ms-2 text-danger fw-bold">*</span></span>
                            <div class="upload_favicon">
                                    <!-- Bouton personnalisé -->
                                  <label for="logoUploadFavicon" class="btn mb-2 mt-2 p-1 btn-light" style="min-width:20px;border-radius: 0;font-size: 13px;">
                                  <!-- <img class="p-1" src="assets/icons/upload_image.png" alt="">-->
                                  <img src="assets/icons/telecharger.png" alt="" style="font-size:16px;" class="me-2">Charger
                                  </label>
                                  <input type="file" id="logoUploadFavicon" accept="image/*" hidden>
                                  <!-- Aperçu -->
                                  <img id="logoPreviewFavicon"
                                       style="height:20px;object-fit:cover;border: 0:" src="" alt="">
                            </div>
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:250px;border: 0;">Image Connexion<span class="ms-2 text-danger fw-bold">*</span></span>
                            <div class="upload_logoLogin">
                                    <!-- Bouton personnalisé -->
                                  <label for="logoUploadLogoLogin" class="btn mb-2 mt-2 p-1 btn-light" style="min-width:20px;border-radius: 0;font-size: 13px;">
                                  <!-- <img class="p-1" src="assets/icons/upload_image.png" alt="">-->
                                  <img src="assets/icons/telecharger.png" alt="" style="font-size:16px;" class="me-2">Charger
                                  </label>
                                  <input type="file" id="logoUploadLogoLogin" accept="image/*" hidden>
                                  <!-- Aperçu -->
                                  <img id="logoPreviewLogoLogin"
                                       style="height:20px;object-fit:cover;border: 0:" src="" alt="">
                            </div>
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2" style="width: 95%;border-bottom: 1px solid #dea700;font-family: 'Segoe UI',serif">
                            <span class="input-group-text fw-bold" style="width:250px;border: 0;">Image / Couleur de Fond<span class="ms-2 text-danger fw-bold">*</span></span>
                            <select  class="mb-3 mt-3" id="type_fond" aria-label="" required aria-describedby="police" style="width: 25%;padding: 0;">
                                    <option value="Image">Image</option>
                                    <option value="Couleur">Couleur</option>
                            </select>
                            <div class="upload_fond ms-4" id="upload_fond">
                                    <!-- Bouton personnalisé -->
                                  <label for="logoUploadFond" class="btn mb-2 mt-2 p-1 btn-light" style="min-width:20px;border-radius: 0;font-size: 13px;">

                                  <img src="assets/icons/telecharger.png" alt="" style="font-size:16px;" class="me-2">Charger
                                  </label>
                                  <input type="file" id="logoUploadFond" accept="image/*" hidden>
                                  <!-- Aperçu -->
                                  <img id="logoPreviewFond"
                                       style="height: 20px;object-fit:cover;border: 0:" src="" alt="">
                            </div>
                            <input type="color" class="form-control fw-bold text-secondary ms-2 mb-3 mt-3" id="fond_application" required aria-label="Code du client" aria-describedby="fond_application"  value="" style="max-width:40px;border: 0;" >
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:250px;border: 0;">Couleur Barre Titre<span class="ms-2 text-danger fw-bold">*</span></span>
                            <select style="width: 25%;padding: 0;" id="couleur_barre_titre"></select>
                            <label class="ms-4" id="lbl_couleur" style="height:30px;width: 100px;border-radius: 25px;border: #2563EB 1px solid;"></label>
                            <!--<input type="color" class="form-control fw-bold text-secondary" style="max-width: 40px;border: 0;" id="couleur_barre_titre" required aria-label="Couleur Barre Titre" aria-describedby="couleur_barre_titre"  value="">-->
                        </div>
                        <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700; ">
                            <span class="input-group-text fw-bold" style="width:250px;border: 0;">Nom Votre application<span class="ms-2 text-danger fw-bold">*</span></span>
                            <input type="text" class="form-control fw-bold text-secondary" id="prefix_application" required aria-label="Code du client" aria-describedby="prefix_application" value="">
                        </div>
                        <div class="m-2 mt-5 p-2" style="background: #ececec;border: 1px solid #ffffff;border-top: 2px solid darkgrey;">
                            <h3>Modèles de documents</h3>

                            <div class="input-group input-group-sm mt-2 ms-2 me-2 pb-2" style="width: 95%;border-bottom: 1px solid #dea700;border-top: 1px solid #dea700;background: rgba(255,255,255,0.50);">
                                <span class="input-group-text fw-bold underline" style="width:250px;border: 0;background: transparent;">Entête de votre structure<span class="ms-2 text-danger fw-bold">*</span></span>
                                <div class="upload_entete">
                                        <!-- Bouton personnalisé -->
                                      <label for="logoUploadEntete" class="btn mb-2 mt-2 mb-5 p-1 btn-light" style="min-width:20px;border-radius: 0;font-size: 13px;">
                                  <!-- <img class="p-1" src="assets/icons/upload_image.png" alt="">-->
                                        <img src="assets/icons/telecharger.png" alt="" style="font-size:16px;" class="me-2">Charger
                                      </label>
                                      <input type="file" id="logoUploadEntete" accept="application/pdf" hidden>
                                      <!-- Aperçu -->
                                           <embed  id="logoPreviewEntete" src="" type="application/pdf" width="500px;" height="200px" />
                                </div>
                            </div>
                        </div>
                       <div class="text-center p-2">
                            <button type="button" class="btn btn-primary p-2" id="btn-enregistrer-scustomize">Enregistrer</button>
                        </div>
                        </form>
                `
            // Files Input Declaration
            let logoUploadIcone = document.getElementById("logoUploadIcone")
            let logoUploadFavicon = document.getElementById("logoUploadFavicon")
            let logoUploadLogoLogin = document.getElementById("logoUploadLogoLogin")
            let logoUploadEntete = document.getElementById("logoUploadEntete")
            let logoUploadFond = document.getElementById("logoUploadFond")


            // Affectation de valeurs
            document.getElementById("police").value = response.police
            document.getElementById("type_icone").value = response.type_icone_application
            document.getElementById("icone_application").value = response.icone_application

            getStateIconApp(response.type_icone_application)
            getStateFondApp(response.type_fond)

            getCouleurApp(document.getElementById("couleur_barre_titre"), document.getElementById("lbl_couleur"))
            setTimeout(()=>{
                document.getElementById("couleur_barre_titre").value = response.couleur_barre_titre;
                document.getElementById("lbl_couleur").style.background = document.getElementById("couleur_barre_titre").value ;
            }, 2000)
            document.getElementById("prefix_application").value = response.prefix_application

            document.getElementById("type_fond").value = response.type_fond_application
            document.getElementById("fond_application").value = response.fond_application


            document.getElementById("logoPreviewIcone").src ="docs/modeles/" + response.icone_application
            document.getElementById("logoPreviewFavicon").src ="docs/modeles/" +  response.favicon
            document.getElementById("logoPreviewLogoLogin").src ="docs/modeles/" +  response.logo_login
            document.getElementById("logoPreviewFond").src ="docs/modeles/" +  response.fond_application
            document.getElementById("logoPreviewEntete").src ="docs/modeles/" +  response.entete


            // Functions
                    function getStateIconApp(value){
                        if (value === "Image"){
                            document.getElementById("upload_icone").style = "display:inline"
                            document.getElementById("icone_application").style = "display:none"
                        } else {
                            document.getElementById("upload_icone").style = "display:none"
                            document.getElementById("icone_application").style = "display:inline"
                        }
                    }
                    function getStateFondApp(value){
                        if (value === "Image"){
                            document.getElementById("upload_fond").style = "display:inline"
                            document.getElementById("fond_application").style = "display:none"
                        } else {
                            document.getElementById("upload_fond").style = "display:none"
                            document.getElementById("fond_application").style = "display:inline"
                        }
                    }


            uploadImage(logoUploadIcone, document.getElementById('logoPreviewIcone'));
            uploadImage(logoUploadFavicon, document.getElementById('logoPreviewFavicon'));
            uploadImage(logoUploadLogoLogin, document.getElementById('logoPreviewLogoLogin'));
            uploadImage(logoUploadEntete, document.getElementById('logoPreviewEntete'));
            uploadImage(logoUploadFond, document.getElementById('logoPreviewFond'));


            $("body").on('change','#type_icone', function(){ getStateIconApp(this.value); })
            $("body").on('change','#type_fond', function(){ getStateFondApp(this.value); })

            // Enregistrement des données de personnalisation
            $("#btn-enregistrer-scustomize").on("click", function (){
                let formData = new FormData()

                formData.append("police",  document.getElementById("police").value)
                formData.append("type_icone", document.getElementById("type_icone").value)
                formData.append("icone_application", document.getElementById("icone_application").value)
                formData.append("couleur_barre_titre", document.getElementById("couleur_barre_titre").value)
                formData.append("prefix_application", document.getElementById("prefix_application").value)
                formData.append("type_fond", document.getElementById("type_fond").value)
                formData.append("fond_application", document.getElementById("fond_application").value)

                // Upload des fichiers
                const files_logoUploadIcone = logoUploadIcone.files;
                const files_logoUploadFavicon = logoUploadFavicon.files;
                const files_logoUploadLogoLogin = logoUploadLogoLogin.files;
                const files_logoUploadEntete = logoUploadEntete.files;
                const files_logoUploadFond = logoUploadFond.files;

                formData.append("filename_icone", files_logoUploadIcone[0]);
                formData.append("filename_favicon", files_logoUploadFavicon[0]);
                formData.append("filename_logo_login", files_logoUploadLogoLogin[0]);
                formData.append("filename_entete", files_logoUploadEntete[0]);
                formData.append("filename_fond", files_logoUploadFond[0]);

                saveInfosCustomize(formData)
            })

        }
    })
}

function uploadImage(button, image){
    button.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Vérification taille < 200Ko
            if (file.size > 1024 * 1024) {
                showToast('L\'image dépasse 1Mo !', {
                    type: 'warning',
                    title: 'Dépassement',
                });
                e.target.value = "";
                return;
            }
            // Aperçu
            const reader = new FileReader();
            reader.onload = function(ev) {
                image.src = ev.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
}

function saveInfosCustomize(formData){
    fetch("/saveInfosCustomize", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            showToast(data.msg, {
                type: data.code,
                title: 'Enregistrment',
                duration: 3000
            });
            if (data.code === 'success') {
                /*getCustomInfos(div_customize)*/
                setTimeout(()=>{
                    document.location.reload();
                }, 3000)

            }
        })
        .catch(err => showToast("❌ Erreur : " + err, {
            type : 'error',
            duration: 3000
        }));
}

function saveCustomize(formData){
    fetch("/saveInfosCustomize", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            showToast(data.msg, {
                type: data.code,
                title: 'Enregistrment',
                duration: 3000
            });
        })
        .catch(err => showToast("❌ Erreur : " + err, {
            type : 'error',
            duration: 3000
        }));
}

function getCouleurApp(ctrl, label) {
    let contenu = ''
        $.ajax({
            url: '/getCouleurApp',
            type: 'POST',
            success: function(response) {
                    let reponse = JSON.parse(response);
                    alert(reponse)
                    contenu = '<option value="0">Sélectionnez un thème</option>'
                    for(var i=0;i < reponse.data.length; i++){
                        contenu += '<option  value="' + reponse.data[i].background + '">' + reponse.data[i].libelle + '</option>'
                    }
                    ctrl.innerHTML = contenu; // renvoie l'objet au .then()
                    ctrl.addEventListener("change", ()=>{ label.style.background = ctrl.value ;}) ; // renvoie l'objet au .then()
            },
            error: function(err) {
                reject(err);
            }
        });
}
