

function getOptions(value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_options = ''
    $.ajax({
        url : '/getOptionsEcbi',
        type : 'POST',
        success: function(response){
            let listeOptions = JSON.parse(response);
            if (listeOptions.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table

                    contenu_options +='<ul class="elegant-list">'
                    for (var i=0; i < listeOptions.data.length ; i++){
                        contenu_options +='<li class="li_option" >' + listeOptions.data[i].libelle + '<input id="v_'+ listeOptions.data[i].id + '" value="'+ listeOptions.data[i].valeur + '" style="border: 1px solid #7c6302;min-width: 75px;float: right;"><a class="enr_option" href="#"  id="'+ listeOptions.data[i].id + '"><i class="fa fa-save"></i></a></li>'
                    }
                    contenu_options +='</ul>'
                } else { // le contrôle est un dropdown
                    contenu_options +='<option value="0">Options ECBI</option>'
                    for (var i=0; i < listeOptions.data.length ; i++){
                        contenu_options +='<option value="'+ listeOptions.data[i].id + '">' + llisteOptions.data[i].libelle  + '</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_options;
            } else {
                render_toast_value(listeOptions.msg, listeOptions.code)
            }
        }
    })
}

    $('body').on('click', '.enr_option', function(){
        let id_input = 'v_'+ this.id
        enregistre_options(this.id, document.getElementById(id_input).value)
    })

    function enregistre_options(id_option, value){
        $.ajax({
            url : 'saveOption/' + id_option + '/' + value,
            type: 'POST',
            success: function (response){
                render_toast_value(response.msg, response.code)
                if (response.code === 'success') {getOptions(1, document.querySelector(".div_options"))}
            }
        })
    }
